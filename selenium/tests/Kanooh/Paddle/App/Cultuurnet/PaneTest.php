<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Cultuurnet\PaneTest.
 */

namespace Kanooh\Paddle\App\Cultuurnet;

use DrupalCultureFeedSearchService;
use Kanooh\Paddle\Apps\Cultuurnet;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminViewPage;
use Kanooh\Paddle\Pages\Node\ViewPage\Cultuurnet\AgendaSearchPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Element\Pane\UiTDatabankPane;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCultuurnet\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Element\PanelsContentType\UiTDatabank\ConfigurationForm;
use Kanooh\Paddle\Pages\Element\PanelsContentType\UiTDatabankPanelsContentType;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the UiTDatabank pane.
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneTest extends WebDriverTestCase
{
    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var AgendaSearchPage
     */
    protected $agendaSearchPage;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * Admin node view page.
     *
     * @var AdminViewPage
     */
    protected $adminViewPage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var DrupalCultureFeedSearchService
     */
    protected $drupalCultureFeedSearchService;

    /**
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;
    protected $uitDataBankPane;
    protected $assetsPath;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Prepare some variables for later use.
        $this->adminViewPage = new AdminViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->layoutPage = new LayoutPage($this);
        $this->assetsPath = !empty($assets_path) ? $assets_path : dirname(__FILE__) . '/../../../../../tests/Kanooh/Paddle/assets';
        $this->configurePage = new ConfigurePage($this);
        $this->agendaSearchPage = new AgendaSearchPage($this);

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Cultuurnet);
    }

    /**
     * Tests the basic configuration and functionality of the UiTDatabank pane.
     *
     * @group panes
     * @group cultuurnet
     */
    public function testPaneConfiguration()
    {
        $this->fillPaddletConfigurationForm();
        // Create a node to use for the panes.
        $nid = $this->contentCreationService->createBasicPage();

        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();
        $panes_before = $region->getPanes();

        // Create an UitDatabankPane and assert that the Search field
        // radiobutton is selected by default.
        $content_type = new UiTDatabankPanelsContentType($this);
        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');

        $modal = new AddPaneModal($this);
        $modal->selectContentType($content_type);

        $this->assertDefaultFormConfiguration($content_type->getForm());

        $modal->submit();
        $modal->waitUntilClosed();
        $region->refreshPaneList();
        $panes_after = $region->getPanes();

        $pane = current(array_diff_key($panes_after, $panes_before));
        $uuid = $pane->getUuid();

        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        $this->layoutPage->go($nid);

        // Open the pane modal again, select the "In the Spotlight"
        // type and assert if the event autocomplete appears.
        $pane = new UiTDatabankPane($this, $uuid);

        $pane->toolbar->buttonEdit->click();
        $pane->editPaneModal->waitUntilOpened();

        $spotlight_radiobutton = $content_type->getForm()->selectionType->spotlight;
        $spotlight_radiobutton->select();
        $this->waitUntilElementIsPresent('//input[@name="event"]');

        // Select the list type and assert if the correct config options appear.

        $list_radiobutton = $content_type->getForm()->selectionType->list;
        $list_radiobutton->select();
        $this->waitUntilElementIsPresent('//input[@name="tag"]');

        // Assert that the title view mode is selected by default.
        $this->assertTrue($content_type->getForm()->viewMode->titles->isSelected());

        // Assert we can choose spotlight mode.
        $list_radiobuttons = $content_type->getForm()->viewMode->spotlight;
        $list_radiobuttons->select();

        // Choose back the default option and exit the modal.
        $content_type->getForm()->selectionType->search->select();

        $pane->editPaneModal->close();
        $pane->editPaneModal->waitUntilClosed();

        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();
    }

    public function testAgendaSearchPage()
    {
        $top_pane_title = $this->alphanumericTestDataProvider->getValidValue(16);
        $bottom_pane_title = $this->alphanumericTestDataProvider->getValidValue(16);
        $this->fillPaddletConfigurationForm($top_pane_title, $bottom_pane_title);
        $this->agendaSearchPage->go();
        $this->agendaSearchPage->checkArrival();
        $this->assertTextPresent($top_pane_title);
        $this->assertTextPresent($bottom_pane_title);
    }

    /*    public function testSpotlight()
        {
            // Mock the method which returns your autocomplete value.
            $this->mockEvent();

            $nid = $this->contentCreationService->createBasicPage();

            $this->layoutPage->go($nid);
            $region = $this->layoutPage->display->getRandomRegion();

            $content_type = new UiTDatabankPanelsContentType($this);
            $region->buttonAddPane->click();

            $modal = new AddPaneModal($this);
            $modal->selectContentType($content_type);

            $content_type->getForm()->selectionType->spotlight->select();
            $this->waitUntilElementIsPresent('//input[@name="event"]');
            $content_type->getForm()->spotlightEvent->fill('ver');

            $autocomplete = new AutoComplete($this);
            $autocomplete->waitUntilDisplayed();
            $autocomplete->pickSuggestionByPosition(0);

            $modal->submit();
            $modal->waitUntilClosed();

            $this->layoutPage->contextualToolbar->buttonSave->click();
            $this->adminViewPage->checkArrival();
        }*/

    /**
     * Checks the default UiTDatabank pane configuration.
     *
     * @param ConfigurationForm $form
     *   The pane configuration form.
     */
    public function assertDefaultFormConfiguration($form)
    {
        // Assert that the 'Search Field' radiobutton has been selected.
        $this->assertTrue($form->selectionType->search->isSelected());

        // Assert that the 'Event' Autocomplete field is not shown.
        try {
            $this->byCssSelector('.form-item.form-type-textfield.form-item-event');
            $this->fail('The event autocomplete should not be shown.');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // Everything is fine.
        }
    }

    public function mockEvent()
    {
        $this->uitDataBankPane = $this->getMockBuilder('Drupal\\paddle_cultuurnet\\UitDatabank')
            ->setMethods(array(
                'paddle_cultuurnet_get_autocomplete_suggestions',
                'paddle_cultuurnet_load_event_by_title',
                'paddle_cultuurnet_prepare_event_for_spotlight'
            ))
            ->getMock();

        $this->uitDataBankPane->expects($this->atLeastOnce())
            ->method('paddle_cultuurnet_get_autocomplete_suggestions')
            ->with($this->equalTo('ver'))
            ->will($this->returnValue(array('Lopen over Vuur' => 'Lopen over Vuur')));

        $this->uitDataBankPane->expects($this->atLeastOnce())
            ->method('paddle_cultuurnet_load_event_by_title')
            ->will($this->returnValue("event"));

        $event_array = array(
            'title' => 'Lopen over Vuur',
            'period' => '25 March',
            'image_url' => $this->assetsPath . '/sample_image.jpg',
        );

        $this->uitDataBankPane->expects($this->atLeastOnce())
            ->method('paddle_cultuurnet_prepare_event_for_spotlight')
            ->will($this->returnValue($event_array));
    }

    /**
     * Fills the CultuurNet configuration form.
     *
     * The method sets the API key and shared secret hard coded
     * but receives input for the search page pane titles.
     *
     * @param $top_pane_title string
     * @param $bottom_pane_title string
     */
    public function fillPaddletConfigurationForm($top_pane_title = '', $bottom_pane_title = '')
    {
        $this->configurePage->go();
        $this->configurePage->checkArrival();
        $this->configurePage->form->applicationKey->fill(variable_get('culturefeed_search_api_application_key'));
        $this->configurePage->form->sharedSecret->fill(variable_get('culturefeed_search_api_shared_secret'));
        if (!empty($top_pane_title)) {
            $this->configurePage->form->topPaneTitle->fill($top_pane_title);
            $this->configurePage->form->topPaneTag->fill('UiTinMijnRegio');
        }
        if (!empty($bottom_pane_title)) {
            $this->configurePage->form->bottomPaneTitle->fill($bottom_pane_title);
            $this->configurePage->form->bottomPaneTag->fill('UiTinMijnRegio');
        }
        $this->configurePage->contextualToolbar->buttonSave->click();
    }
}
