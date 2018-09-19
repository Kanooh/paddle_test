<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Core\Themer\SearchSettingsTest.
 */

namespace Kanooh\Paddle\Core\Themer;

use Kanooh\Paddle\Apps\GoogleCustomSearch;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerAddPage\ThemerAddPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ThemerEditPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\Pane\GoogleCustomSearchPane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\GoogleCustomSearchPanelsContentType;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndViewPage;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Test all themer search settings.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class SearchSettingsTest extends WebDriverTestCase
{
    /**
     * The administrative node view.
     *
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * The homepage.
     *
     * @var FrontPage
     */
    protected $frontPage;

    /**
     * The front-end view of a landing page.
     *
     * @var FrontEndViewPage
     */
    protected $frontendPage;

    /**
     * The panels content edit page of a landing page.
     *
     * @var PanelsContentPage
     */
    protected $panelsContentPage;

    /**
     * The 'Add' page of the Paddle Themer module.
     *
     * @var ThemerAddPage
     */
    protected $themerAddPage;

    /**
     * The 'Edit' page of the Paddle Themer module.
     *
     * @var ThemerEditPage
     */
    protected $themerEditPage;

    /**
     * The 'Overview' page of the Paddle Themer module.
     *
     * @var ThemerOverviewPage
     */
    protected $themerOverviewPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The alphanumeric test data provider.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->frontendPage = new FrontEndViewPage($this);
        $this->frontPage = new FrontPage($this);
        $this->panelsContentPage = new PanelsContentPage($this);
        $this->themerAddPage = new ThemerAddPage($this);
        $this->themerEditPage = new ThemerEditPage($this);
        $this->themerOverviewPage = new ThemerOverviewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as site manager.
        $this->userSessionService->login('SiteManager');

        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new GoogleCustomSearch);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Log out.
        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->logout();

        parent::tearDown();
    }

    /**
     * Tests the search labels.
     *
     * @group themer
     */
    public function testSearchLabels()
    {
        // Create a new theme.
        $this->themerAddPage->go();
        $this->themerAddPage->baseTheme->selectOptionByValue('vo_standard');
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();
        $theme_name = $this->themerEditPage->getThemeName();
        $this->themerEditPage->header->header->click();
        // Check that the search settings for the texts are enabled by default.
        $this->assertTrue($this->themerEditPage->header->standardSearchButtonLabelEnabled->isChecked());
        $this->assertTrue($this->themerEditPage->header->standardSearchPlaceholderTextEnabled->isChecked());

        // Save the theme and enable it.
        $this->moveto($this->themerEditPage->buttonSubmit);
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();
        $this->themerOverviewPage->theme($theme_name)->enable->click();
        $this->themerOverviewPage->checkArrival();

        // Go to the frontend and check the labels.
        $this->frontPage->go();
        $this->frontPage->searchBox->checkPlaceholder('Looking for what?');
        $this->assertEquals('Search', $this->frontPage->searchBox->searchButton->attribute('value'));

        // Now check for the google custom search pane that the labels are correct.
        $nid = $this->contentCreationService->createLandingPage();
        $this->panelsContentPage->go($nid);
        $region = $this->panelsContentPage->display->getRandomRegion();
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

        $google_pane = new GoogleCustomSearchPane($this, $pane->getUuid(), $pane->getXPathSelector());
        $this->panelsContentPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        $this->frontendPage->go($nid);
        $google_pane = new GoogleCustomSearchPane($this, $pane->getUuid(), '//div[@data-pane-uuid="' . $google_pane->getUuid() . '"]');
        $this->assertTrue($google_pane->checkPlaceholder('Looking for what?'));
        $this->assertEquals('Search', $google_pane->searchButton->attribute('value'));

        // Now Change the labels search.
        $this->themerEditPage->go($theme_name);
        $this->themerEditPage->header->header->click();
        // Uncheck the checkboxes and change the values of the texts.
        $this->themerEditPage->header->standardSearchPlaceholderTextEnabled->waitUntilDisplayed();
        $this->themerEditPage->header->standardSearchPlaceholderTextEnabled->uncheck();
        $this->themerEditPage->header->standardSearchButtonLabelEnabled->uncheck();
        $placeholder = $this->alphanumericTestDataProvider->getValidValue(8);
        $label = $this->alphanumericTestDataProvider->getValidValue(8);
        $this->themerEditPage->header->standardSearchPlaceholderText->waitUntilDisplayed();
        $this->themerEditPage->header->standardSearchPlaceholderText->fill($placeholder);
        $this->themerEditPage->header->standardSearchButtonLabel->waitUntilDisplayed();
        $this->themerEditPage->header->standardSearchButtonLabel->fill($label);
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();

        // Go to the frontend and check the labels.
        $this->frontPage->go();
        $this->frontPage->searchBox->checkPlaceholder($placeholder);
        $this->frontPage->searchBox->checkSearchButtonLabel($label);

        $this->frontendPage->go($nid);
        $google_pane = new GoogleCustomSearchPane($this, $pane->getUuid(), '//div[@data-pane-uuid="' . $google_pane->getUuid() . '"]');
        $this->assertTrue($google_pane->checkPlaceholder($placeholder));
        $this->assertEquals($label, $google_pane->searchButton->attribute('value'));
    }

    /**
     * Tests the search popup.
     *
     * @group themer
     */
    public function testSearchPopUp()
    {
        // Create a new theme.
        $this->themerAddPage->go();
        $this->themerAddPage->baseTheme->selectOptionByValue('vo_standard');
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();
        $theme_name = $this->themerEditPage->getThemeName();
        $this->moveto($this->themerEditPage->header->header);
        $this->themerEditPage->header->header->click();
        // Check that the search settings for the texts are enabled by default.
        $this->assertFalse($this->themerEditPage->header->searchBoxPopUpEnabled->isChecked());

        // Save the theme and enable it.
        $this->moveto($this->themerEditPage->buttonSubmit);
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();
        $this->themerOverviewPage->theme($theme_name)->enable->click();
        $this->themerOverviewPage->checkArrival();

        // Go to the frontend and check the labels.
        $this->frontPage->go();
        // Make sure we see the search box.
        $this->frontPage->searchBox->checkPlaceholder('Looking for what?');

        $this->themerEditPage->go($theme_name);
        $this->moveto($this->themerEditPage->header->header);
        $this->themerEditPage->header->header->click();
        $this->themerEditPage->header->standardSearchPlaceholderTextEnabled->waitUntilDisplayed();
        $this->themerEditPage->header->searchBoxPopUpEnabled->check();
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();

        // Go to the frontend and check that the search icon is shown.
        $this->frontPage->go();

        // Make sure the search button is not visible.
        $this->assertTextNotPresent('search');
        $this->assertTrue($this->frontPage->searchBox->checkSearchPopUpIcon('search'));

        // Now click on the search icon
        $xpath = '//a[contains(@class, "search-pop-up")]/i[contains(@class, "fa-search")]';
        $this->byXPath($xpath)->click();

        // Make sure you get the x icon.
        $this->assertTrue($this->frontPage->searchBox->checkSearchPopUpIcon('times'));

        // and that we can see the search button.
        $this->assertTextPresent('search');
        $this->frontPage->searchBox->isPresent();
    }
}
