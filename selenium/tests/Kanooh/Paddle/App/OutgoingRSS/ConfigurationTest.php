<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OutgoingRSS\ConfigurationTest.
 */

namespace Kanooh\Paddle\App\OutgoingRSS;

use Jeremeamia\SuperClosure\SerializableClosure;
use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Apps\OutgoingRSS;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleOutgoingRSS\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\OutgoingRSS\RSSFeedSettingsModal;
use Kanooh\Paddle\Pages\Element\OutgoingRSS\RSSFeedDeleteModal;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs configuration tests on the Outgoing RSS paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ConfigurationTest extends WebDriverTestCase
{
    /**
     * The administrative node view page.
     *
     * @var ViewPage
     */
    protected $adminNodeViewPage;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * The paddlet configuration page.
     *
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * Instance of the ContentCreationService used to create content.
     *
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * Random data generator.
     *
     * @var Random
     */
    protected $random;

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
        $this->adminNodeViewPage = new ViewPage($this);
        $this->configurePage = new ConfigurePage($this);
        $this->random = new Random();
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new OutgoingRSS);
    }

    /**
     * Tests the add, edit and delete operations on Outgoing RSS feeds.
     */
    public function testRSSFeedAddEditDelete()
    {
        // Create some terms. Define a taxonomy data array where the key is the
        // vocabulary name and the value is a the term name.
        $taxonomy_data = array(
            'paddle_tags' => $this->random->name(8),
            'paddle_general' => $this->random->name(8),
        );

        foreach ($taxonomy_data as $voc_name => $term_name) {
            $options = array(
                'vid' => taxonomy_vocabulary_machine_name_load($voc_name)->vid,
                'name' => $term_name,
            );

            $term = (object) $options;
            taxonomy_term_save($term);
        }

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Go to the configuration page and check the number of RSS feeds already
        // present on the page.
        $this->configurePage->go();
        $starting_feeds = $this->configurePage->feedTable->getNumberOfRows();
        if ($this->isTextPresent('No Outgoing RSS feeds have been created yet.')) {
            $starting_feeds = 0;
        }

        // Create new RSS feed.
        $this->configurePage->contextualToolbar->buttonCreate->click();
        $modal = new RSSFeedSettingsModal($this);
        $modal->waitUntilOpened();

        // Check that the title and content types are required.
        $modal->form->saveButton->click();
        $this->waitUntilTextIsPresent('RSS feed title field is required.');
        $this->waitUntilTextIsPresent('Content types field is required.');

        // Now fill in the data and save.
        $feed_title = $this->random->name(8);
        $modal->form->title->fill($feed_title);
        $modal->form->basicPageCheckBox->check();
        $modal->form->landingPageCheckBox->check();
        $modal->form->filterTags->fill($taxonomy_data['paddle_tags']);
        $autocomplete = new AutoComplete($this);
        $autocomplete->pickSuggestionByPosition(0);
        $modal->form->saveButton->click();

        // Wait until the row appears.
        $this->configurePage->feedTable->waitUntilTableUpdated($feed_title);

        // Check the content types.
        $row = $this->configurePage->feedTable->getRowByTitle($feed_title);
        $this->assertEquals('Basic page, Landing Page', $row->contentTypes);

        // Now edit the feed, change the already filled data and add more.
        $row->linkEdit->click();
        $modal = new RSSFeedSettingsModal($this);
        $modal->waitUntilOpened();

        // Check the data we entered.
        $this->assertEquals($feed_title, $modal->form->title->getContent());
        $this->assertTrue($modal->form->basicPageCheckBox->isChecked());
        $this->assertTrue($modal->form->landingPageCheckBox->isChecked());
        $this->assertFalse($modal->form->overviewPageCheckBox->isChecked());
        $this->assertEquals($taxonomy_data['paddle_tags'], $modal->form->filterTags->getContent());
        $this->assertEquals('', $modal->form->filterTerms->getContent());

        // Now change the data in the form.
        $new_feed_title = $this->random->name(8);
        $modal->form->title->fill($new_feed_title);
        $modal->form->basicPageCheckBox->uncheck();
        $modal->form->overviewPageCheckBox->check();

        $modal->form->filterTerms->fill($taxonomy_data['paddle_general']);
        $autocomplete = new AutoComplete($this);
        $autocomplete->pickSuggestionByPosition(0);

        $modal->form->saveButton->click();

        // Wait until the row is changed.
        $this->configurePage->feedTable->waitUntilTableUpdated($new_feed_title);

        $row = $this->configurePage->feedTable->getRowByTitle($new_feed_title);
        $this->assertEquals('Landing Page, Overview page', $row->contentTypes);

        // Now edit the feed to verify the data was changed correctly.
        $row->linkEdit->click();
        $modal = new RSSFeedSettingsModal($this);
        $modal->waitUntilOpened();

        // Check the data we entered.
        $this->assertEquals($new_feed_title, $modal->form->title->getContent());
        $this->assertFalse($modal->form->basicPageCheckBox->isChecked());
        $this->assertTrue($modal->form->landingPageCheckBox->isChecked());
        $this->assertTrue($modal->form->overviewPageCheckBox->isChecked());
        $this->assertEquals($taxonomy_data['paddle_tags'], $modal->form->filterTags->getContent());
        $this->assertEquals($taxonomy_data['paddle_general'], $modal->form->filterTerms->getContent());

        // Check the number of feeds in the table is correct.
        $this->assertEquals($starting_feeds + 1, $this->configurePage->feedTable->getNumberOfRows());

        $modal->close();
        $modal->waitUntilClosed();

        // Create one more RSS feed.
        $this->configurePage->contextualToolbar->buttonCreate->click();
        $modal = new RSSFeedSettingsModal($this);
        $modal->waitUntilOpened();
        $second_feed_title = $this->random->name(8);
        $modal->form->title->fill($second_feed_title);
        $modal->form->basicPageCheckBox->check();
        $modal->form->saveButton->click();

        // Wait until the row appears.
        $this->configurePage->feedTable->waitUntilTableUpdated($second_feed_title);

        // Check the number of feeds in the table is correct.
        $this->assertEquals($starting_feeds + 2, $this->configurePage->feedTable->getNumberOfRows());

        // Try deleting the row by cancel it.
        $first_row = $this->configurePage->feedTable->getRowByTitle($new_feed_title);
        $first_row->linkDelete->click();
        $delete_modal = new RSSFeedDeleteModal($this);
        $delete_modal->waitUntilOpened();
        $delete_modal->buttonCancel->click();
        $delete_modal->waitUntilClosed();

        // Check that the row is still there.
        $first_row = $this->configurePage->feedTable->getRowByTitle($new_feed_title);
        $this->assertNotNull($first_row);

        // Now delete if for real.
        $first_row->linkDelete->click();
        $delete_modal = new RSSFeedDeleteModal($this);
        $delete_modal->waitUntilOpened();
        $delete_modal->buttonConfirm->click();
        $delete_modal->waitUntilClosed();
        $this->assertNull($this->configurePage->feedTable->getRowByTitle($new_feed_title));

        // Delete the second feed as well.
        $second_row = $this->configurePage->feedTable->getRowByTitle($second_feed_title);
        $second_row->linkDelete->click();
        $delete_modal = new RSSFeedDeleteModal($this);
        $delete_modal->waitUntilOpened();
        $delete_modal->buttonConfirm->click();
        $delete_modal->waitUntilClosed();
        $this->assertNull($this->configurePage->feedTable->getRowByTitle($second_feed_title));

        // Check the number of feeds in the table is correct.
        $final_number_feeds = $this->configurePage->feedTable->getNumberOfRows();
        if ($this->isTextPresent('No Outgoing RSS feeds have been created yet.')) {
            $final_number_feeds = 0;
        }
        $this->assertEquals($starting_feeds, $final_number_feeds);
    }

    /**
     * Tests the generation of the path on which the RSS feeds are displayed.
     */
    public function testRSSFeedPath()
    {
        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor', true);

        // Create the nodes we expect to appear in the RSS.
        $nodes = array();
        for ($i = 0; $i < 5; $i++) {
            $node_title = $i . $this->random->name(8);
            $node_body = $this->random->name(300);
            $nid = $this->contentCreationService->createBasicPage($node_title);
            // Edit the node to change the body and author.
            $values = array(
                'body' => $node_body,
                'uid' => $this->userSessionService->getCurrentUserId(),
                'status' => 1,
            );
            $this->contentCreationService->editNode($nid, $values);

            // Get the publication date from the admin node page.
            $this->adminNodeViewPage->go($nid);
            $publication_date = $this->adminNodeViewPage->nodeSummary->getMetadata('publication', 'publish');
            $nodes[$nid] = array('title' => $node_title, 'body' => $node_body, 'publication_date' => $publication_date);
        }

        $titles = array($this->random->name(8), $this->random->name(8));
        for ($i = 0; $i < 2; $i++) {
            $this->configurePage->go();

            // Create a RSS feed.
            $this->configurePage->contextualToolbar->buttonCreate->click();
            $modal = new RSSFeedSettingsModal($this);
            $modal->waitUntilOpened();
            $modal->form->title->fill($titles[$i]);
            $modal->form->basicPageCheckBox->check();
            $modal->form->saveButton->click();
            $this->configurePage->feedTable->waitUntilTableUpdated($titles[$i]);

            $this->assertRssFeed($titles[$i], $nodes);
        }

        // Make sure the first path is still working fine as there was a menu
        // rebuild after the second RSS feed creation.
        $this->configurePage->go();
        $this->assertRssFeed($titles[0], $nodes);
    }

    /**
     * Wait until a row in the table with the passed title appears.
     *
     * @param  string $feed_title
     *   The title of the feed.
     */
    public function waitUntilTableUpdated($feed_title)
    {
        $config_page = $this->configurePage;
        $callable = new SerializableClosure(
            function () use ($config_page, $feed_title) {
                if ($config_page->feedTable->getRowByTitle($feed_title)) {
                    return true;
                }
            }
        );
        $this->waitUntil($callable, $this->getTimeout());
    }

    /**
     * Check the table row and the content of the RSS feed page for a RSS feed.
     *
     * @param string $rss_title
     *   The expected title of the RSS feed.
     * @param array $nodes
     *   The items of the RSS feed. Each element has the nid as key and the
     *   node title, body and publication date in an array as value.
     */
    public function assertRssFeed($rss_title, $nodes)
    {
        $row = $this->configurePage->feedTable->getRowByTitle($rss_title);

        // Check that the path is "<feed:title>/rss.xml". We just need to
        // lower-case the title as the random string generator will generate
        // only alpha-numeric strings.
        $rss_path = strtolower($rss_title) . '/rss.xml';
        $this->assertEquals($rss_path, $row->path);

        $row->linkPreview->click();

        // Wait until the page is loaded that way as Selenium proved
        // incapable of finding XML tags on the page. Just find the RSS
        // feed description.
        $rss_description = $rss_title . ' - ' . variable_get('site_name', '');
        $this->waitUntilTextIsPresent($rss_description);

        // Check the content of the RSS.
        $this->assertTextPresent($rss_title);

        // Get the source of the page we are on (should be a RSS feed page).
        // We cannot use $this->source() to get the source code of the
        // current page as the result is browser specific.
        $source = file_get_contents($this->url());

        // Replace the <dc:author> tag with <author> as it will be removed by
        // simplexml_load_string() and we will lose it.
        $source = str_replace('dc:creator', 'author', $source);
        $xml = simplexml_load_string($source);
        $rss = json_decode(json_encode($xml), true);

        // Now check the structure of the RSS.
        $this->assertTrue(is_array($rss));
        $this->assertEquals($rss['channel']['title'], $rss_title);
        $this->assertEquals($rss['channel']['description'], $rss_description);
        $this->assertTrue(strpos($rss['channel']['link'], $rss_path) !== false);

        // Now check that the passed nodes are found in the RSS. Since there
        // might be other nodes from before we will check if the ones we expect
        // to be there are indeed there instead of checking if what we found is
        // all what we expect.
        $username = $this->userSessionService->getCurrentUserName();
        foreach ($nodes as $nid => $data) {
            foreach ($rss['channel']['item'] as $key => $item) {
                if ($item['guid'] == $nid) {
                    $this->assertEquals($item['title'], $data['title']);
                    $this->assertTrue(strpos($item['description'], $data['body']) !== false);
                    $this->assertEquals($item['title'], $data['title']);
                    $this->assertEquals($item['author'], $username);

                    // Format the date according RFC-822.
                    $rfc_date = format_date($data['publication_date']['value_raw'], 'custom', 'r');
                    $this->assertEquals($item['pubDate'], $rfc_date);
                }
            }
        }
    }
}
