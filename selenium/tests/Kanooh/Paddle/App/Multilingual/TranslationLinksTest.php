<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Multilingual\TranslationLinksTest
 */

namespace Kanooh\Paddle\App\Multilingual;

use Kanooh\Paddle\Apps\Multilingual;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMultilingual\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\CreateNodeModal;
use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPage as ContentManagerPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPageContentTableRow;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\Node\EditPage\TranslationTableRow;
use Kanooh\Paddle\Pages\Node\TranslatePage\TranslatePage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\MultilingualService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Checks the presence of the links to the translation node page.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class TranslationLinksTest extends WebDriverTestCase
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
     * The paddlet configuration page.
     *
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var ContentManagerPage
     */
    protected $contentManagerPage;

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
        $this->configurePage = new ConfigurePage($this);
        $this->contentManagerPage = new ContentManagerPage($this);
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
     * Tear down method executed after the test is done.
     */
    public function tearDown()
    {
        // Log out.
        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->logout();

        parent::tearDown();
    }

    /**
     * Tests presence and functioning of the translation link.
     */
    public function testContentManagerTranslationLink()
    {
        $nid = $this->contentCreationService->createBasicPage();

        // Go to the Content manager.
        $this->contentManagerPage->go();

        // Verify that the archive link is still present.
        /** @var SearchPageContentTableRow $row */
        $row = $this->contentManagerPage->contentTable->getNodeRowByNid($nid);
        $this->assertTrue($row->links->linkArchive->displayed());

        // Click the translation lnk for this node.
        $row->links->linkTranslate->click();
        $this->translatePage->checkArrival();
        $this->translatePage->contextualToolbar->buttonSave->click();
    }

    /**
     * Tests presence and functioning of the translation link.
     */
    public function testAdminNodeViewTranslationLink()
    {
        $nid = $this->contentCreationService->createBasicPage();

        // Go to the admin node view page for this node.
        $this->administrativeNodeViewPage->go($nid);

        // Click the translation link for this node.
        $this->administrativeNodeViewPage->contextualToolbar->buttonTranslations->click();
        $this->translatePage->checkArrival();
        $this->translatePage->contextualToolbar->buttonBack->click();
        $this->acceptAlert();
        $this->administrativeNodeViewPage->checkArrival();
    }

    /**
     * Tests presence and functioning of the "Add translation" links on the edit
     * node and translation pages.
     */
    public function testAddTranslationLinks()
    {
        $supported_languages = paddle_i18n_supported_languages();

        // Switch to Dutch.
        $this->contentManagerPage->go();
        $this->contentManagerPage->languageSwitcher->switchLanguage('nl');

        // Create an original node which will be translated.
        $original_title = $this->alphanumericTestDataProvider->getValidValue();
        $original_nid = $this->contentCreationService->createBasicPage($original_title);

        // Translate the original from the node edit page by adding a
        // translation in French first and then from the node translation page
        // by adding a translation in English.
        $pages = array('fr' => $this->editPage, 'en' => $this->translatePage);
        foreach ($pages as $lang_code => $page) {
            /** @var EditPage|TranslatePage $page */
            $page->go($original_nid);
            /** @var TranslationTableRow $translation_row */
            $translation_row = $page->translationTable->getRowByLanguage($lang_code);
            $translation_row->translationLink->click();
            $modal = new CreateNodeModal($this);
            $modal->waitUntilOpened();

            // Check that by default the title is copied from the original.
            $this->assertEquals($original_title, $modal->title->getContent());
            $modal->title->clear();
            $fr_title = $this->alphanumericTestDataProvider->getValidValue();
            $modal->title->fill($fr_title);
            $modal->submit();
            $modal->waitUntilClosed();

            // We should arrive at the admin node page of the translation page.
            $this->administrativeNodeViewPage->checkArrival();
            $fr_nid = $this->administrativeNodeViewPage->getNodeIDFromUrl();
            $this->editPage->go($fr_nid);
            $this->assertEquals($fr_title, $this->editPage->title->value());
            $this->editPage->contextualToolbar->buttonSave->click();
            $this->administrativeNodeViewPage->checkArrival();

            $this->administrativeNodeViewPage->nodeSummary->showAllMetadata();
            $page_language = $this->administrativeNodeViewPage->nodeSummary->getMetadataItem('structure', 'language');
            $this->assertNotNull($page_language);

            $this->assertEquals('Language:' . $supported_languages[$lang_code], $page_language->text());

            // Check that the page is indeed a translation of the original.
            drupal_static_reset('translation_node_get_translations');
            $translations = translation_node_get_translations($original_nid);
            $this->assertContains($lang_code, array_keys($translations));
        }
    }
}
