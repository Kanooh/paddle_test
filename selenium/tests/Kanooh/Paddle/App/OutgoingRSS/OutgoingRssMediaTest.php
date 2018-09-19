<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OutgoingRSS\OutgoingRssMediaTest.
 */

namespace Kanooh\Paddle\App\OutgoingRSS;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\IncomingRSS;
use Kanooh\Paddle\Apps\News;
use Kanooh\Paddle\Apps\OutgoingRSS;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleIncomingRSS\ConfigurePage\ConfigurePage as IncomingRSSConfigurePage;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleOutgoingRSS\ConfigurePage\ConfigurePage as OutgoingRSSConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminViewPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\IncomingRSS\RSSFeedItem;
use Kanooh\Paddle\Pages\Element\IncomingRSS\RSSFeedModal;
use Kanooh\Paddle\Pages\Element\OutgoingRSS\RSSFeedSettingsModal;
use Kanooh\Paddle\Pages\Element\Pane\IncomingRssPane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\IncomingRssPanelsContentType;
use Kanooh\Paddle\Pages\Element\Scald\LibraryModal;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\TaxonomyService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests integration with the Outgoing RSS to show images in xml feeds.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 *
 * @package Kanooh\Paddle\App\OutgoingRSS
 */
class OutgoingRssMediaTest extends WebDriverTestCase
{

    /**
     * @var AdminViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var EditPage
     */
    protected $editPage;

    /**
     * @var ViewPage
     */
    protected $frontendPage;

    /**
     * @var IncomingRSSConfigurePage
     */
    protected $incomingRSSConfigurePage;

    /**
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * @var OutgoingRSSConfigurePage
     */
    protected $outgoingRSSConfigurePage;

    /**
     * @var TaxonomyService
     */
    protected $taxonomyService;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Prepare some variables for later use.
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->administrativeNodeViewPage = new AdminViewPage($this);
        $this->assetCreationService = new AssetCreationService($this);
        $this->editPage = new EditPage($this);
        $this->frontendPage = new ViewPage($this);
        $this->incomingRSSConfigurePage = new IncomingRSSConfigurePage($this);
        $this->layoutPage = new LayoutPage($this);
        $this->outgoingRSSConfigurePage = new OutgoingRSSConfigurePage($this);
        $this->taxonomyService = new TaxonomyService();
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as a site manager.
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new News);
        $this->appService->enableApp(new OutgoingRSS);
        $this->appService->enableApp(new IncomingRSS);
    }

    /**
     * Tests that Outgoing RSS feeds for news items include the lead image.
     *
     * @group News
     */
    public function testOutgoingRSSImage()
    {
        // Create a taxonomy term to easily filter all the test nodes in the
        // Outgoing RSS feed.
        $term_title = $this->alphanumericTestDataProvider->getValidValue();
        $tid = $this->taxonomyService->createTerm(
            TaxonomyService::GENERAL_TAGS_VOCABULARY_ID,
            $term_title
        );

        // Create a news item without image.
        $nodes = array();
        $info = $this->createTestNode('NewsItem', $tid);
        $nodes[$info['nid']] = $info;

        // Create a basic page and a news item with an image.
        $atom = $this->assetCreationService->createImage();
        $info = $this->createTestNode('BasicPage', $tid, $atom);
        $nodes[$info['nid']] = $info;

        $atom = $this->assetCreationService->createImage();
        $info = $this->createTestNode('NewsItem', $tid, $atom);
        $nodes[$info['nid']] = $info;

        // Create an outgoing rss filtered by term.
        $this->outgoingRSSConfigurePage->go();
        $this->outgoingRSSConfigurePage->contextualToolbar->buttonCreate->click();
        $modal = new RSSFeedSettingsModal($this);
        $modal->waitUntilOpened();

        $feed_title = $this->alphanumericTestDataProvider->getValidValue();
        $modal->form->title->fill($feed_title);
        $modal->form->basicPageCheckBox->check();
        $modal->form->newsItemCheckBox->check();
        $modal->form->selectStyle->selectOptionByValue('16_9');
        $modal->form->filterTerms->fill($term_title);
        $autocomplete = new AutoComplete($this);
        $autocomplete->pickSuggestionByValue($term_title);
        $modal->form->saveButton->click();
        $this->outgoingRSSConfigurePage->feedTable->waitUntilTableUpdated($feed_title);

        // Load the xml file.
        $row = $this->outgoingRSSConfigurePage->feedTable->getRowByTitle($feed_title);
        $rss_url = $row->linkPreview->attribute('href');
        $xml = simplexml_load_file($rss_url);

        // To fetch media information, we have to extract namespaces first.
        $namespaces = $xml->getNamespaces(true);

        // Verify that the media namespace is present.
        $this->assertArrayHasKey('media', $namespaces);

        // Check that we have the four items. We cannot use assertCount()
        // because the SimpleXMLElement class is not a proper iterator.
        $this->assertEquals(3, count($xml->channel->item));

        foreach ($xml->channel->item as $item) {
            /* @var \SimpleXMLElement $item */
            $nid = (string) $item->guid;
            $this->assertEquals($nodes[$nid]['title'], (string) $item->title);

            // Retrieve the media information.
            $media = $item->children($namespaces['media']);

            if (isset($nodes[$nid]['atom'])) {
                $this->assertAtomProperties($nodes[$nid]['atom'], $media->content->attributes());
            } else {
                $this->assertEmpty($media, "The xml item for node $nid contains unwanted media information.");
            }
        }

        // Create a new Incoming RSS.
        $this->incomingRSSConfigurePage->go();
        $this->incomingRSSConfigurePage->contextualToolbar->buttonAdd->click();
        $modal = new RSSFeedModal($this);
        $modal->waitUntilOpened();

        $incoming_rss_title = $this->alphanumericTestDataProvider->getValidValue();
        $modal->form->title->fill($incoming_rss_title);
        $modal->form->url->fill($rss_url);
        $modal->submit();
        $modal->waitUntilClosed();
        $this->waitUntilTextIsPresent($incoming_rss_title);

        // Create a basic page and add the Incoming rss pane to it.
        $nid = $this->contentCreationService->createBasicPage();

        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();

        $content_type = new IncomingRssPanelsContentType($this);
        $callable = new SerializableClosure(
            function () use ($content_type, $incoming_rss_title) {
                $content_type->getForm()->incomingRssFeeds->selectOptionByLabel($incoming_rss_title);
                $content_type->getForm()->magazineViewMode->select();
            }
        );
        $pane_element = $region->addPane($content_type, $callable);

        // Save the page and go to the frontend view.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->frontendPage->go($nid);

        // Retrieve the elements from the pane.
        $pane = new IncomingRssPane($this, $pane_element->getUuid());

        // Key Incoming RSS feed items by title to allow easier assertionts.
        $feed_items = array();
        foreach ($pane->getFeedItems() as $item) {
            /* @var \Kanooh\Paddle\Pages\Element\IncomingRSS\RSSFeedItem $item */
            $feed_items[$item->title] = $item;
        }

        // Assert that the fetched items are correct.
        foreach ($nodes as $info) {
            $this->assertArrayHasKey($info['title'], $feed_items);
            $item = $feed_items[$info['title']];

            if (isset($info['atom'])) {
                $this->assertEquals($info['atom']['expected_url'], $item->thumbnail->attribute('src'));
            } else {
                $this->assertFalse(
                    $item->thumbnail,
                    "The feed item for node {$info['nid']} contains unwanted thumbnail."
                );
            }
        }
    }

    /**
     * Creates a node for test purposes.
     *
     * @param string $type
     *   The type of the node we want to create, in Pascal case.
     * @param int $tid
     *   The id of the taxonomy term to tag the node with.
     * @param null|array $atom
     *   The atom to use as leading image. Works only for news item nodes.
     * @return array
     *   Information data about the node created, with title, nid, and atom.
     */
    protected function createTestNode($type, $tid, $atom = null)
    {
        $title = $this->alphanumericTestDataProvider->getValidValue();

        $method = 'create' . $type;
        $nid = $this->contentCreationService->{$method}($title);

        // Prepare the information to be returned by the method.
        $info = array(
            'title' => $title,
            'nid' => $nid,
        );

        // Edit the node to add the taxonomy term to it.
        $this->editPage->go($nid);
        $this->editPage->generalVocabularyTermReferenceTree->selectTerm($tid);

        // Add some random text to the body to have the xml more filled.
        $this->editPage->body->setBodyText($this->alphanumericTestDataProvider->getValidValue());

        if ($atom) {
            $this->moveto($this->editPage->body->getWebdriverElement());
            $this->editPage->featuredImage->selectAtom($atom['id']);
            $scald_atom = entity_load_single('scald_atom', $atom['id']);
            $atom_wrapper = entity_metadata_wrapper('scald_atom', $scald_atom);
            $scald_thumbnail = $atom_wrapper->scald_thumbnail->value();
            $url = file_create_url($scald_thumbnail['uri']);

            // We replace the path variables so that it will be located as 16:9 image dimensions.
            // Which it should be after selecting the style in the RSS feed item.
            $style_16_9_url = str_replace('/files/', '/files/styles/16_9/public/', $url);
            $info['atom'] = array(
                'entity' => $scald_atom,
                'expected_url' => $style_16_9_url,
            );
        }

        // Save and publish the node, to show it in the feeds.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        return $info;
    }

    /**
     * Asserts xml media properties to match the atom ones.
     *
     * @param array $expected
     *   Atom information array.
     * @param \SimpleXMLElement $actual
     *   The xml attributes element.
     */
    protected function assertAtomProperties($expected, $actual)
    {
        // Get the scald atom.
        $atom = $expected['entity'];
        $atom_wrapper = entity_metadata_wrapper('scald_atom', $atom);
        $scald_thumbnail = $atom_wrapper->scald_thumbnail->value();

        $this->assertEquals($scald_thumbnail['filemime'], (string) $actual->type);

        // Assert that the correct image is outputted.
        $this->assertEquals($expected['expected_url'], (string) $actual->url);
    }
}
