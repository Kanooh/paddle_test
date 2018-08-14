<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Multilingual\LanguageSwitcherTest
 */

namespace Kanooh\Paddle\App\Multilingual;

use Kanooh\Paddle\Apps\Multilingual;
use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPage as ContentManagerPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Admin\DashboardPage\DashboardPage;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage\MenuOverviewPage;
use Kanooh\Paddle\Pages\Admin\Structure\TaxonomyManager\OverviewPage\OverviewPage as TaxonomyOverviewPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\Node\TranslatePage\TranslatePage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndNodeViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\MultilingualService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on the functioning of the Language switcher block.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class LanguageSwitcherTest extends WebDriverTestCase
{
    /**
     * @var ViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * Test data provider for alphanumeric data.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var ContentManagerPage
     */
    protected $contentManagerPage;

    /**
     * @var DashboardPage
     */
    protected $dashboardPage;

    /**
     * The node edit page.
     *
     * @var EditPage
     */
    protected $editPage;

    /**
     * @var FrontEndNodeViewPage
     */
    protected $frontEndNodeViewPage;

    /**
     * @var FrontPage
     */
    protected $homePage;

    /**
     * @var MenuOverviewPage
     */
    protected $menuOverviewPage;

    /**
     * @var TaxonomyOverviewPage
     */
    protected $taxonomyOverviewPage;

    /**
     * @var ThemerOverviewPage
     */
    protected $themerOverviewPage;

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
        $this->contentManagerPage = new ContentManagerPage($this);
        $this->dashboardPage = new DashboardPage($this);
        $this->editPage = new EditPage($this);
        $this->homePage = new FrontPage($this);
        $this->frontEndNodeViewPage = new FrontEndNodeViewPage($this);
        $this->menuOverviewPage = new MenuOverviewPage($this);
        $this->taxonomyOverviewPage = new TaxonomyOverviewPage($this);
        $this->themerOverviewPage = new ThemerOverviewPage($this);
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
     * Tests the pages on which the language switcher is present/absent.
     */
    public function testLanguageSwitcherPages()
    {
        // Check that the language switcher is not present on these pages. It
        // was present before but now we have removed it from these pages.
        $this->dashboardPage->go();
        $this->assertLanguageSwitcherNotPresent();

        $en_nid = $this->contentCreationService->createBasicPage();
        $this->editPage->go($en_nid);
        $this->assertLanguageSwitcherNotPresent();
        $this->editPage->language->selectOptionByValue('en');
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->dashboardPage->go();

        // Now check the presence of the Language switcher on the Menu manager
        // and the Content manager pages.
        $this->menuOverviewPage->go();
        $this->assertNotNull($this->menuOverviewPage->languageSwitcher);
        $this->assertEquals('nl', $this->menuOverviewPage->getInterfaceLanguage());

        // Now check that the language of the menu switches and the correct menu
        // items are displayed.
        $this->menuOverviewPage->languageSwitcher->switchLanguage('en');
        $en_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->menuOverviewPage->createMenuItem(array('title' => $en_title));

        $this->menuOverviewPage->languageSwitcher->switchLanguage('nl');
        $this->menuOverviewPage->checkArrival();
        $this->assertTextNotPresent($en_title);

        $nl_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->menuOverviewPage->createMenuItem(array('title' => $nl_title));

        $this->menuOverviewPage->languageSwitcher->switchLanguage('en');
        $this->menuOverviewPage->checkArrival();
        $this->assertTextNotPresent($nl_title);

        $this->menuOverviewPage->languageSwitcher->switchLanguage('fr');
        $this->menuOverviewPage->checkArrival();
        $this->assertTextNotPresent($en_title);
        $this->assertTextNotPresent($nl_title);

        // Go back to NL.
        $this->menuOverviewPage->languageSwitcher->switchLanguage('en');
        $this->menuOverviewPage->checkArrival();

        $this->contentManagerPage->go();
        $this->assertNotNull($this->contentManagerPage->languageSwitcher);

        // The page created previously in the test should be present here.
        $this->assertNotFalse($this->contentManagerPage->contentTable->getNodeRowByNid($en_nid));

        // Check that the Content manager respects languages.
        $this->contentManagerPage->languageSwitcher->switchLanguage('nl');
        $this->contentManagerPage->checkArrival();

        // Create a node via the UI so that the language is respected.
        $nl_nid = $this->contentCreationService->createBasicPageViaUI();
        $this->contentManagerPage->go();
        $this->assertFalse($this->contentManagerPage->contentTable->getNodeRowByNid($en_nid));
        $this->assertNotFalse($this->contentManagerPage->contentTable->getNodeRowByNid($nl_nid));

        $this->contentManagerPage->languageSwitcher->switchLanguage('fr');
        $this->contentManagerPage->checkArrival();

        $this->assertFalse($this->contentManagerPage->contentTable->getNodeRowByNid($nl_nid));
        $this->assertFalse($this->contentManagerPage->contentTable->getNodeRowByNid($en_nid));

        // Return to NL so as not to break other tests.
        $this->contentManagerPage->languageSwitcher->switchLanguage('nl');
        $this->contentManagerPage->checkArrival();

        // Do the test for the taxonomy manager.
        $this->taxonomyOverviewPage->go(2);
        $this->assertNotNull($this->taxonomyOverviewPage->languageSwitcher);
        $this->assertEquals('nl', $this->taxonomyOverviewPage->languageSwitcher->getActiveLanguage());

        // Now check that the language of the taxonomy switches and the correct
        // terms are displayed.
        $nl_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->taxonomyOverviewPage->createTerm(array('name' => $nl_title));

        $this->taxonomyOverviewPage->languageSwitcher->switchLanguage('en');
        $this->taxonomyOverviewPage->checkArrival();
        $this->assertTextNotPresent($nl_title);

        $en_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->taxonomyOverviewPage->createTerm(array('name' => $en_title));

        $this->taxonomyOverviewPage->languageSwitcher->switchLanguage('nl');
        $this->taxonomyOverviewPage->checkArrival();
        $this->assertTextNotPresent($en_title);
        $this->assertTextPresent($nl_title);

        $this->taxonomyOverviewPage->languageSwitcher->switchLanguage('fr');
        $this->taxonomyOverviewPage->checkArrival();
        $this->assertTextNotPresent($en_title);
        $this->assertTextNotPresent($nl_title);

        // Go back to NL.
        $this->taxonomyOverviewPage->languageSwitcher->switchLanguage('nl');
        $this->taxonomyOverviewPage->checkArrival();

        // Check that the language switcher is not present on the pages on which
        // it is shown by default if the paddlet is disabled.
        $app = new Multilingual;
        $this->appService->disableAppsByMachineNames(array($app->getModuleName()));
        foreach (array('menuOverview', 'contentManager', 'taxonomyOverview') as $page) {
            $this->{$page . 'Page'}->go();
            $this->assertLanguageSwitcherNotPresent();
        }
    }

    /**
     * Tests the frontend language switcher functionality.
     */
    public function testFrontendLanguageSwitcher()
    {
        // Make sure the tests have the expected multilingual configuration.
        MultilingualService::setPaddleTestDefaults($this);

        // Create an English basic page.
        $basic_en_title = $this->alphanumericTestDataProvider->getValidValue();
        $basic_en = $this->contentCreationService->createBasicPage($basic_en_title);
        $this->contentCreationService->changeNodeLanguage($basic_en, 'en');
        $this->administrativeNodeViewPage->go($basic_en);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Create a basic page and a homepage in Dutch.
        $basic_nl_title = $this->alphanumericTestDataProvider->getValidValue();
        $basic_nl = $this->contentCreationService->createBasicPage($basic_nl_title);
        $this->contentCreationService->changeNodeLanguage($basic_nl, 'nl');
        $this->administrativeNodeViewPage->go($basic_nl);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        $home_nl_title = $this->alphanumericTestDataProvider->getValidValue();
        $home_nl = $this->contentCreationService->createBasicPage($home_nl_title);
        $this->contentCreationService->changeNodeLanguage($home_nl, 'nl');
        $this->administrativeNodeViewPage->go($home_nl);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Set the Dutch page as translation for the English one.
        $this->translatePage->go($basic_en);
        $this->translatePage->selectExistingTranslation('nl', $basic_nl_title);
        $this->translatePage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Go to the frontend English page.
        $this->frontEndNodeViewPage->go($basic_en);

        // Assert that this is our English node but the switcher is in Dutch.
        $this->assertEquals('nl', $this->frontEndNodeViewPage->languageSwitcher->getActiveLanguage());
        $this->assertTextPresent($basic_en_title);

        // Switch the language to English.
        $this->frontEndNodeViewPage->languageSwitcher->switchLanguage('en');
        $this->frontEndNodeViewPage->checkArrival();

        // Assert that the current node is the English one.
        $this->assertTextPresent($basic_en_title);

        // Switch the language to Dutch.
        $this->frontEndNodeViewPage->languageSwitcher->switchLanguage('nl');
        $this->frontEndNodeViewPage->checkArrival();

        // Assert that the current node is the English one.
        $this->assertTextPresent($basic_nl_title);

        // Assert that the other two languages have no links.
        $this->assertNull($this->frontEndNodeViewPage->languageSwitcher->getLanguageLink('fr'));
        $this->assertNull($this->frontEndNodeViewPage->languageSwitcher->getLanguageLink('de'));

        // Create a French home page.
        $home_fr_title = $this->alphanumericTestDataProvider->getValidValue();
        $basic_fr = $this->contentCreationService->createBasicPage($home_fr_title);
        $this->contentCreationService->changeNodeLanguage($basic_fr, 'fr');
        $this->administrativeNodeViewPage->go($basic_fr);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Set the French page as translation for the Dutch homepage.
        $this->translatePage->go($home_nl);
        $this->translatePage->selectExistingTranslation('fr', $home_fr_title);
        $this->translatePage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->checkArrival();

        // Set the homepage.
        variable_set('site_frontpage', "node/{$home_nl}");

        // Go to the frontend English page.
        $this->frontEndNodeViewPage->go($basic_en);

        // Assert that this is our English node.
        $this->assertEquals('nl', $this->frontEndNodeViewPage->languageSwitcher->getActiveLanguage());
        $this->assertTextPresent($basic_en_title);

        // Switch the language to English.
        $this->frontEndNodeViewPage->languageSwitcher->switchLanguage('en');
        $this->frontEndNodeViewPage->checkArrival();

        // Assert that the current node is the English one.
        $this->assertTextPresent($basic_en_title);

        // Switch the language to Dutch.
        $this->frontEndNodeViewPage->languageSwitcher->switchLanguage('nl');
        $this->frontEndNodeViewPage->checkArrival();

        // Assert that the current node is the Dutch one.
        $this->assertTextPresent($basic_nl_title);

        // Now verify that the French link points to the French homepage, as
        // there is no translation for this node in French. Since we want to
        // test also the German language link later, we cannot open the link
        // in the same window as the language switcher links would change.
        $webdriver = $this;
        $callable = function () use ($webdriver, $home_fr_title) {
            $webdriver->assertTextPresent($home_fr_title);
        };
        $this->openInNewWindow(
            $this->frontEndNodeViewPage->languageSwitcher->getLanguageLink('fr')->attribute('href'),
            $callable
        );

        // Switch the language to German.
        $this->frontEndNodeViewPage->languageSwitcher->switchLanguage('de');
        $this->frontEndNodeViewPage->checkArrival();
        $this->assertTextPresent($home_nl_title);
    }

    /**
     * Test the language switcher as select box.
     */
    public function testSwitcherSelect()
    {
        $enabled_languages = i18n_language_list('name');

        // If we have less than 5 languages enable some.
        if (count($enabled_languages) < 5) {
            MultilingualService::enableLanguages($this, array('Bulgarian'));
            drupal_static_reset('language_list');
            $enabled_languages = i18n_language_list('name');
        }

        // Create a Dutch and English node.
        $nl_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->contentCreationService->createBasicPage($nl_title);

        $en_title = $this->alphanumericTestDataProvider->getValidValue();
        $en_nid = $this->contentCreationService->createBasicPage($en_title);
        $this->contentCreationService->changeNodeLanguage($en_nid, 'en');

        // Verify that the switcher is a select and that it works.
        $this->contentManagerPage->go();
        $this->assertTrue($this->contentManagerPage->languageSwitcher->isDisplayed());
        $languages = $this->contentManagerPage->languageSwitcher->getAllLanguages();

        // Verify that the correct languages are in the select.
        $diff = array_diff($languages, array_keys($enabled_languages));
        $this->assertEmpty($diff);

        // Verify that the NL is currently selected.
        $this->assertEquals('nl', $this->contentManagerPage->languageSwitcher->getActiveLanguage());

        // Verify that it works.
        $this->contentManagerPage->languageSwitcher->switchLanguage('en');
        $this->contentManagerPage->checkArrival();
        // Verify that the EN is currently selected.
        $this->assertEquals('en', $this->contentManagerPage->languageSwitcher->getActiveLanguage());
        $this->assertTextPresent($en_title);
        $this->assertTextNotPresent($nl_title);

        $this->contentManagerPage->languageSwitcher->switchLanguage('nl');
        $this->contentManagerPage->checkArrival();

        // Verify that the NL is currently selected.
        $this->assertEquals('nl', $this->contentManagerPage->languageSwitcher->getActiveLanguage());
        $this->assertTextNotPresent($en_title);
        $this->assertTextPresent($nl_title);
    }

    /**
     * Tests the language switcher shown on mobile viewports.
     */
    public function testFrontendMobileSwitcher()
    {
        // Enable one additional language to be sure to have the menu shown.
        MultilingualService::enableLanguages($this, array('Italian'));

        // Create a basic page and its translation.
        $source_title = $this->alphanumericTestDataProvider->getValidValue();
        $source_nid = $this->contentCreationService->createBasicPage($source_title);
        $italian_title = $this->alphanumericTestDataProvider->getValidValue();
        $italian_nid = $this->contentCreationService->createBasicPage($italian_title);
        $this->contentCreationService->changeNodeLanguage($italian_nid, 'it');
        $this->administrativeNodeViewPage->go($italian_nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Set the Italian page as translation for the default language one.
        $this->translatePage->go($source_nid);
        $this->translatePage->selectExistingTranslation('it', $italian_title);
        $this->translatePage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();

        // Go to the frontend source node.
        $this->frontEndNodeViewPage->go($source_nid);

        // Verify that now the switcher is hidden.
        $this->assertFalse($this->frontEndNodeViewPage->mobileLanguageSwitcher->isDisplayed());

        // Save all available language for later.
        $switcher_languages = $this->frontEndNodeViewPage->languageSwitcher->getAllLanguages();

        // Resize the browser window to show the mobile switcher.
        $this->resizeCurrentWindow(500);

        // Verify that now the switcher is visible.
        $this->assertTrue($this->frontEndNodeViewPage->mobileLanguageSwitcher->isDisplayed());

        // Verify that the languages available are the same.
        $this->assertEquals(
            $switcher_languages,
            $this->frontEndNodeViewPage->mobileLanguageSwitcher->getAllLanguages()
        );

        // Verify that the active language is the default one.
        $this->assertEquals('nl', $this->frontEndNodeViewPage->mobileLanguageSwitcher->getActiveLanguage());
        $this->assertTextPresent($source_title);

        // Change language to Italian.
        $this->frontEndNodeViewPage->mobileLanguageSwitcher->switchLanguage('it');
        $this->frontEndNodeViewPage->checkArrival();
        $this->frontEndNodeViewPage->go($italian_nid);

        // Verify that the node is now the Italian one.
        $this->assertTextPresent($italian_title);
    }

    /**
     * Data provider for the themes to test.
     */
    public function themeDataProvider()
    {
        return array(
            array('vo_strict'),
            array('go_theme'),
            array('kanooh_theme_v2'),
            array('kanooh_theme_v2_page_wide'),
            array('kanooh_theme_v2_vertical_navigation'),
            // Leave default theme last for other tests.
            array('vo_standard'),
        );
    }

    /**
     * Tests that the language switcher is correctly shown in all themes.
     *
     * @dataProvider themeDataProvider
     *
     * @group regression
     */
    public function testFrontendLanguageSwitcherInTheme($theme_name)
    {
        // Log in as site manager.
        $this->userSessionService->switchUser('SiteManager');

        // Enable the go_theme if needed. This will save some speed on other
        // themes tests.
        if ($theme_name == 'go_theme') {
            module_enable(array('paddle_go_themes'));
            drupal_flush_all_caches();
        }

        // Preview the wanted theme.
        $this->themerOverviewPage->go();
        if ($this->themerOverviewPage->getActiveTheme()->machineName != $theme_name) {
            $this->themerOverviewPage->theme($theme_name)->enable->click();
            $this->themerOverviewPage->checkArrival();
        }

        $this->homePage->go();
        $this->assertTrue($this->homePage->languageSwitcher->isDisplayed());

        // Resize the browser window to show the mobile switcher.
        $current_size = $this->currentWindow()->size();
        $this->currentWindow()->size(array('width' => 500, 'height' => $current_size['height']));

        // Verify that now the switcher is visible.
        $this->assertTrue($this->frontEndNodeViewPage->mobileLanguageSwitcher->isDisplayed());
    }

    /**
     * Since not all our pages extend AdminPage to which the LanguageSwitcher
     * object has been added we need a separate method to assert the absence of
     * the Language switcher.
     */
    protected function assertLanguageSwitcherNotPresent()
    {
        $xpath = '//ul[contains(@class, "language-switcher-locale-url")]';
        $this->assertCount(0, $this->elements($this->using('xpath')->value($xpath)));
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Set the frontpage variable back to default.
        variable_set('site_frontpage', 'placeholder');
    }
}
