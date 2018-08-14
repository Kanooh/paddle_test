<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Multilingual\NodeLanguageTest
 */

namespace Kanooh\Paddle\App\Multilingual;

use Kanooh\Paddle\Apps\Multilingual;
use Kanooh\Paddle\Pages\Node\EditPage\TranslationTableRow;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\Node\TranslatePage\TranslatePage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\MultilingualService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on the language of a node.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeLanguageTest extends WebDriverTestCase
{
    /**
     * Test data provider for alphanumeric data.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * App service.
     *
     * @var AppService
     */
    protected $appService;

    /**
     * @var ViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The node edit page.
     *
     * @var EditPage
     */
    protected $editPage;

    /**
     * @var TranslatePage
     */
    protected $translatePage;

    /**
     * User session service.
     *
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Instantiate some classes to use in the test.
        $this->administrativeNodeViewPage = new ViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->editPage = new EditPage($this);
        $this->translatePage = new TranslatePage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as site manager.
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not enabled yet.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Multilingual);

        // Make sure the tests have the expected multilingual configuration.
        MultilingualService::setPaddleTestDefaults($this);
    }

    /**
     * Tests the presence/changing of the language in the node summary.
     */
    public function testNodeLanguage()
    {
        $nid = $this->contentCreationService->createBasicPage();

        // By default the language should be NL and this should be shown already
        // in the metadata, so check that it is. First on admin node view page.
        $this->assertLanguageMetadata($nid, $this->administrativeNodeViewPage, 'structure', 'nl');

        // And then on the node edit page.
        $this->assertLanguageMetadata($nid, $this->editPage, 'general', 'nl');

        // Now change the language to DE.
        $this->editPage->language->selectOptionByValue('de');
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Check the metadata again.
        $this->assertLanguageMetadata($nid, $this->administrativeNodeViewPage, 'structure', 'de');
        $this->assertLanguageMetadata($nid, $this->editPage, 'general', 'de');

        // Check that the value in the edit page is correct as well.
        $this->assertEquals('de', $this->editPage->language->getSelectedValue());
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
    }

    /**
     * Tests the rendering and functioning of the Translation overview pane.
     */
    public function testTranslationsOverview()
    {
        $nodes = array();
        $languages = array('nl', 'en', 'de');
        foreach ($languages as $language) {
            $title = $this->alphanumericTestDataProvider->getValidValue();
            $nid = $this->contentCreationService->createBasicPage($title);
            $this->contentCreationService->changeNodeLanguage($nid, $language);
            $nodes[$language] = array('nid' => $nid, 'title' => $title);
        }

        // Check the translation table before the node gets translated.
        $this->editPage->go($nodes['nl']['nid']);
        $languages = language_list('enabled');
        unset($languages[1]['nl']);
        foreach (array_keys($languages[1]) as $enabled_language) {
            /** @var TranslationTableRow $row */
            $row = $this->editPage->translationTable->getRowByLanguage($enabled_language);
            $this->assertNull($row->nodeLink);
        }

        $this->translatePage->go($nodes['nl']['nid']);
        foreach (array('en', 'de') as $language_code) {
            $this->translatePage->selectExistingTranslation($language_code, $nodes[$language_code]['title']);
        }

        // Save the translations.
        $this->translatePage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Go to the node edit page to check the translations overview pane.
        $this->editPage->go($nodes['nl']['nid']);
        foreach (array('en', 'de') as $language_code) {
            $node = $nodes[$language_code];
            /** @var \Kanooh\Paddle\Pages\Node\EditPage\TranslationTableRow $row */
            $row = $this->editPage->translationTable->getRowByLanguage($language_code);
            $this->assertNotNull($row->nodeLink);
            $this->assertEquals($node['title'], $row->nodeLink->text());
            $this->assertContains(strtolower($node['title']), $row->nodeLink->attribute('href'));

            $this->assertNotNull($row->translationLink);
            $this->assertEquals('edit', $row->translationLink->text());
            $this->assertContains("node/{$node['nid']}/edit", $row->translationLink->attribute('href'));
        }
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
    }

    /**
     * Asserts that the node's language is correctly displayed in the metadata.
     *
     * @param $nid
     *   The node id for which to check.
     * @param ViewPage|EditPage $page
     *   The page on which to check.
     * @param $group
     *   The metadata group in which the language should to be found.
     * @param $language
     *   The expected language of the node.
     */
    protected function assertLanguageMetadata($nid, $page, $group, $language)
    {
        $page->go($nid);
        $metadata_language = $page->nodeSummary->getMetadata($group, 'language');
        $this->assertEquals($language, $metadata_language['value_raw']);
    }
}
