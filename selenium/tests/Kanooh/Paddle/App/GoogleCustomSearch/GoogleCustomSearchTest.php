<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\GoogleCustomSearch\GoogleCustomSearchTest.
 */

namespace Kanooh\Paddle\App\GoogleCustomSearch;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Apps\GoogleCustomSearch;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleGoogleCustomSearch\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage as AddContentPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Element\Layout\Paddle2Col3to9Layout;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\Pane\GoogleCustomSearchPane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\GoogleCustomSearchPanelsContentType;
use Kanooh\Paddle\Pages\Node\ViewPage\LandingPageViewPage;
use Kanooh\Paddle\Pages\SearchPage\GoogleCustomSearchPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerAddPage\ThemerAddPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ThemerEditPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Pages\SearchPage\PaddleSearchPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on the Paddle Google Custom Search Paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class GoogleCustomSearchTest extends WebDriverTestCase
{
    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * @var ConfigurePage
     */
    protected $googleCustomSearchConfigPage;

    /**
     * The random data generator.
     *
     * @var Random
     */
    protected $random;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var AddContentPage
     */
    protected $addContentPage;

    /**
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var PanelsContentPage
     */
    protected $landingPageLayoutPage;

    /**
     * @var LandingPageViewPage
     */
    protected $landingViewPage;

    /**
     * @var string
     */
    protected $theme_name;

    /**
     * @var ThemerAddPage
     */
    protected $themerAddPage;

    /**
     * @var ThemerEditPage
     */
    protected $themerEditPage;

    /**
     * @var ThemerOverviewPage
     */
    protected $themerOverviewPage;

    /**
     * @var FrontPage
     */
    protected $frontPage;

    /**
     * @var GoogleCustomSearchPage
     */
    protected $googleCustomSearchPage;

    /**
     * @var PaddleSearchPage
     */
    protected $paddleSearchPage;

    /**
     * The search engine refinement labels.
     *
     * @var array
     */
    protected $labels = array(
        0 => array('label' => 'more:news', 'anchor' => 'News'),
        1 => array('label' => 'more:prijzen', 'anchor' => 'prijzen'),
        2 => array('label' => 'more:wiki', 'anchor' => 'Wiki'),
    );

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->addContentPage = new AddContentPage($this);
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->googleCustomSearchConfigPage = new ConfigurePage($this);
        $this->googleCustomSearchPage = new GoogleCustomSearchPage($this);
        $this->landingPageLayoutPage = new PanelsContentPage($this);
        $this->landingViewPage = new LandingPageViewPage($this);
        $this->frontPage = new FrontPage($this);
        $this->googleCustomSearchConfigPage = new ConfigurePage($this);
        $this->googleCustomSearchPage = new GoogleCustomSearchPage($this);
        $this->paddleSearchPage = new PaddleSearchPage($this);
        $this->random = new Random();
        $this->themerOverviewPage = new ThemerOverviewPage($this);
        $this->themerAddPage = new ThemerAddPage($this);
        $this->themerEditPage = new ThemerEditPage($this);

        // Go to the login page and log in as site manager.
        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->login('SiteManager');

        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new GoogleCustomSearch);

        // Add a new theme.
        $this->themerOverviewPage->go();

        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();

        // Create a new theme.
        $human_theme_name = $this->random->name(8);
        $this->themerAddPage->name->clear();
        $this->themerAddPage->name->value($human_theme_name);
        $this->themerAddPage->baseTheme->selectOptionByValue('vo_standard');
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();
        $this->theme_name = $this->themerEditPage->getThemeName();

        // Unfold the header section and enable the google custom search.
        $this->themerEditPage->header->header->click();
        $this->waitUntilTextIsPresent('Google custom search');
        $this->moveto($this->themerEditPage->header->googleCustomSearchEnabled->getWebdriverElement());
        $this->themerEditPage->header->googleCustomSearchEnabled->check();
        $this->moveto($this->themerEditPage->buttonSubmit);
        $this->themerEditPage->buttonSubmit->click();

        // Enable the theme.
        $this->themerOverviewPage->checkArrival();
        $theme = $this->themerOverviewPage->theme($this->theme_name);
        $this->assertEquals($human_theme_name, $theme->title->text());
        $theme->enable->click();
        $this->themerOverviewPage->checkArrival();
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Disable the Google custom Search.
        $this->themerOverviewPage->go();
        $theme = $this->themerOverviewPage->theme($this->theme_name);
        $theme->edit->click();
        $this->themerEditPage->checkArrival();
        $this->themerEditPage->header->header->click();
        $this->themerEditPage->header->googleCustomSearchEnabled->uncheck();
        $this->themerEditPage->header->paddleSearchEnabled->check();
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();
        // Log out.
        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->logout();

        parent::tearDown();
    }

    /**
     * Tests the configuration form for the google custom search paddlet.
     *
     * @group search
     */
    public function testConfigurationForm()
    {
        // Go to the configuration page.
        $this->googleCustomSearchConfigPage->go();
        // Build an array with a value for each form field with woeful keys to
        // test the warning messages.
        $values = array(
            'cse_id' => 'no_proper_id',
            'api_key' => 'no_proper_api_key',
        );

        // Fill in the form with the values specified previously.
        $this->googleCustomSearchConfigPage->cseID->fill($values['cse_id']);
        $this->googleCustomSearchConfigPage->apiKey->fill($values['api_key']);

        // Save the form and verify that the values have been saved.
        $this->googleCustomSearchConfigPage->contextualToolbar->buttonSave->click();
        $this->googleCustomSearchConfigPage->checkArrival();
        $this->waitUntilTextIsPresent('The configuration options have been saved.');

        // Check that the values have been set correctly.
        $this->assertEquals($values['cse_id'], $this->googleCustomSearchConfigPage->cseID->getContent());
        $this->assertEquals($values['api_key'], $this->googleCustomSearchConfigPage->apiKey->getContent());

        // Test if the wrong API key / engine id exception is being thrown.
        $this->frontPage->go();
        $this->frontPage->searchBox->searchMethod->googleCustomSearch->select();
        $this->frontPage->searchBox->searchField->clear();
        // Search with a keyword that will give back a bunch of results.
        $this->frontPage->searchBox->searchField->fill('Test');
        $this->frontPage->searchBox->searchButton->click();
        $this->googleCustomSearchPage->checkArrival();
        $this->assertTextPresent('The API key or Engine ID you entered in the configuration screen of the google custom search paddlet is not correct.');

        // Go to the configuration page.
        $this->googleCustomSearchConfigPage->go();

        // Fill in the form with empty values.
        $this->googleCustomSearchConfigPage->cseID->fill('');
        $this->googleCustomSearchConfigPage->apiKey->fill('');
        $this->googleCustomSearchConfigPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The configuration options have been saved.');

        // Test if the maximum requests exception is being thrown.
        $this->frontPage->go();
        $this->frontPage->searchBox->searchMethod->googleCustomSearch->select();
        $this->frontPage->searchBox->searchField->clear();
        // Search with a keyword that will give back a bunch of results.
        $this->frontPage->searchBox->searchField->fill('Test');
        $this->frontPage->searchBox->searchButton->click();
        $this->googleCustomSearchPage->checkArrival();
        $this->assertTextPresent('Your limit of 100 requests has been reached, if you want more requests, please upgrade to a paid plan.');
    }

    /**
     * Tests the pagination on the google custom search page.
     *
     * @group search
     */
    public function testPagination()
    {
        // Go to the configuration page.
        $this->googleCustomSearchConfigPage->go();
        // Fill in the form with correct keys.
        $this->googleCustomSearchConfigPage->cseID->fill('002208523045865629998:-5f9imrvx-g');
        $this->googleCustomSearchConfigPage->apiKey->fill('AIzaSyD1IRykPL_z9vPbAR6i_PHzyXRIQFO1cfE');

        // Save the form and verify that the values have been saved.
        $this->googleCustomSearchConfigPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The configuration options have been saved.');

        // Search google custom search and verify that on the google custom
        // search page the pager is present.
        $this->frontPage->go();
        $this->frontPage->searchBox->searchMethod->googleCustomSearch->select();
        $this->frontPage->searchBox->searchField->clear();
        // Search with a keyword that will give back a bunch of results.
        $this->frontPage->searchBox->searchField->fill('Test');
        $this->frontPage->searchBox->searchButton->click();
        $this->googleCustomSearchPage->checkArrival();
        $this->googleCustomSearchPage->checkSearchResultsPresent();
        // The previous link and page one link should not be shown.
        $this->googleCustomSearchPage->pager->checkLinks(array('Next', 'PageTwo'));

        // Click next and check if you get google custom search results.
        $this->googleCustomSearchPage->pager->linkNext->click();
        $this->googleCustomSearchPage->checkArrival();
        // The page two link should not be shown.
        $this->googleCustomSearchPage->pager->checkLinks(array('Next', 'PageOne', 'Previous'));
        $this->googleCustomSearchPage->checkSearchResultsPresent();

        // Click previous and check if you get google custom search results.
        $this->googleCustomSearchPage->pager->linkPrevious->click();
        $this->googleCustomSearchPage->checkArrival();
        // The previous link and page one link should not be shown.
        $this->googleCustomSearchPage->pager->checkLinks(array('Next', 'PageTwo'));
        $this->googleCustomSearchPage->checkSearchResultsPresent();

        // Click page 2 and check if you get google custom search results.
        $this->googleCustomSearchPage->pager->linkPageTwo->click();
        $this->googleCustomSearchPage->checkArrival();
        // The page two link should not be shown.
        $this->googleCustomSearchPage->pager->checkLinks(array('Next', 'PageOne', 'Previous'));
        $this->googleCustomSearchPage->checkSearchResultsPresent();

        // Click page 1 and check if you get google custom search results.
        $this->googleCustomSearchPage->pager->linkPageOne->click();
        $this->googleCustomSearchPage->checkArrival();
        // The previous link and page one link should not be shown.
        $this->googleCustomSearchPage->pager->checkLinks(array('Next', 'PageTwo'));
        $this->googleCustomSearchPage->checkSearchResultsPresent();
    }

    /**
     * Tests the refinement labels on the google custom search page.
     *
     * @group search
     */
    public function testRefinementLabels()
    {
        // Go to the configuration page.
        $this->googleCustomSearchConfigPage->go();

        // Fill in the form with correct keys.
        $this->googleCustomSearchConfigPage->cseID->fill('002369531751305550084:nnujzea9xum');
        $this->googleCustomSearchConfigPage->apiKey->fill('AIzaSyAnFQG4QhxYCQiU4qPa7McI90kjBzvbLGg');

        // Save the form and verify that the values have been saved.
        $this->googleCustomSearchConfigPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The configuration options have been saved.');

        $this->frontPage->go();
        $this->frontPage->searchBox->searchMethod->googleCustomSearch->select();
        $this->frontPage->searchBox->searchField->clear();
        $this->frontPage->searchBox->searchField->fill('Test');
        $this->frontPage->searchBox->searchButton->click();
        $this->googleCustomSearchPage->checkArrival();

        $index = 0;
        do {
            $label_links = $this->googleCustomSearchPage->labels->getLabelLinks();
            $link = $label_links[$index];
            $url = $link->attribute('href');
            $this->assertTrue(strpos($url, 'label=' . urlencode($this->labels[$index]['label'])) !== false);
            $link->click();
            $this->googleCustomSearchPage->checkArrival();
            $this->assertEquals($url, $this->url());

            // Get the links to try to find the active label link if there were results.
            if (!$this->isTextPresent('No matching content has been found.')) {
                $label_links = $this->googleCustomSearchPage->labels->getLabelLinks();
                $link = $label_links[$index];
                $classes = explode(" ", $link->attribute('class'));
                $this->assertTrue(in_array('active-facet', $classes));
            }

            // Go back to the initial search result page.
            $this->googleCustomSearchPage->searchForm->googleSearchRadioButton->select();
            $this->googleCustomSearchPage->searchForm->keywords->fill('Test');
            $this->googleCustomSearchPage->searchForm->submit->click();
            $this->googleCustomSearchPage->waitUntilPageIsLoaded();

            // Search with a keyword that will give back a bunch of results.
            $this->googleCustomSearchPage->checkSearchResultsPresent();

            // Check the number of labels displayed.
            $this->assertCount(count($this->labels), $label_links);
            $index++;
        } while (isset($label_links[$index]));
    }

    /**
     * Tests the google_custom search pane.
     *
     * @group panes
     * @group search
     */
    public function testPane()
    {
        // Go to the configuration page.
        $this->googleCustomSearchConfigPage->go();
        // Build an array with a value for each form field.
        $values = array(
            'cse_id' => '005128381786780307400:stfmksqq510',
            'api_key' => 'AIzaSyBHJZs8IY5uCde9rSTSPfwHgUnuo45x_98',
        );

        // Fill in the form with the values specified previously.
        $this->googleCustomSearchConfigPage->cseID->fill($values['cse_id']);
        $this->googleCustomSearchConfigPage->apiKey->fill($values['api_key']);

        // Save the form and verify that the values have been saved.
        $this->googleCustomSearchConfigPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The configuration options have been saved.');

        // Create a landing page.
        $this->addContentPage->go();
        $layout = new Paddle2Col3to9Layout();
        $this->addContentPage->createLandingPage($layout->id());

        // Add an organizational unit pane to the landing page.
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageLayout->click();
        $this->landingPageLayoutPage->checkArrival();
        $region = $this->landingPageLayoutPage->display->getRandomRegion();
        $google_custom_search_pane = new GoogleCustomSearchPanelsContentType($this);

        $panes_before = $region->getPanes();

        // Open the Add Pane dialog.
        $region->buttonAddPane->click();

        // Select the pane type in the modal dialog.
        $modal = new AddPaneModal($this);
        $modal->waitUntilOpened();
        $this->waitUntilTextIsPresent('Add new pane');
        $modal->selectContentType($google_custom_search_pane);
        $modal->submit();
        $modal->waitUntilClosed();

        $region->refreshPaneList();
        $panes_after = $region->getPanes();

        $pane = current(array_diff_key($panes_after, $panes_before));

        $this->assertInstanceOf('Kanooh\\Paddle\\Pages\\Element\\Pane\\Pane', $pane);

        $google_pane = new GoogleCustomSearchPane($this, $pane->getUuid(), $pane->getXPathSelector());
        $this->assertTrue($google_pane->searchField->isDisplayed());
        $this->assertTrue($google_pane->searchButton->displayed());
        $this->assertFalse($google_pane->searchField->isEnabled());
        $this->assertFalse($google_pane->searchButton->enabled());
        $this->landingPageLayoutPage->contextualToolbar->buttonSave->click();

        $this->administrativeNodeViewPage->checkArrival();
        $google_pane = new GoogleCustomSearchPane($this, $pane->getUuid(), '//div[contains(@class, "pane-google-custom-search")]');
        $this->assertTrue($google_pane->searchField->isDisplayed());
        $this->assertTrue($google_pane->searchButton->displayed());
        $this->assertFalse($google_pane->searchField->isEnabled());
        $this->assertFalse($google_pane->searchButton->enabled());

        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->landingViewPage->checkArrival();
        $google_pane = new GoogleCustomSearchPane($this, $pane->getUuid(), '//div[@data-pane-uuid="' . $google_pane->getUuid() . '"]');
        $this->assertTrue($google_pane->searchField->isDisplayed());
        $this->assertTrue($google_pane->searchButton->displayed());

        // Now perform a search.
        $google_pane->searchField->clear();
        $google_pane->searchField->fill($this->random->name(6));
        $google_pane->searchButton->click();
        $this->googleCustomSearchPage->checkArrival();
    }
}
