<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Embed\PaneTest.
 */

namespace Kanooh\Paddle\App\Embed;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Apps\Embed;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleEmbed\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Element\Embed\WidgetDeleteModal;
use Kanooh\Paddle\Pages\Element\Embed\WidgetSettingsModal;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\PanelsContentType\EmbedWidgetPanelsContentType;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the widget pane.
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneTest extends WebDriverTestCase
{
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
     * Landing page layout page.
     *
     * @var PanelsContentPage
     */
    protected $layoutPage;

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
     * Front end node view page.
     *
     * @var ViewPage
     */
    protected $viewPage;

    /**
     * Test data provider for alphanumeric data.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Prepare some variables for later use.
        $this->adminViewPage = new AdminViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->configurePage = new ConfigurePage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->layoutPage = new PanelsContentPage($this);
        $this->random = new Random();
        $this->viewPage = new ViewPage($this);

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Embed);
    }

    /**
     * Tests the basic configuration and functionality of the pane.
     *
     * @group panes
     * @group embed
     */
    public function testPane()
    {
        // Log in as site manager.
        $this->userSessionService->login('SiteManager');

        // Go to the configuration page and create a widget.
        $this->configurePage->go();
        $this->configurePage->contextualToolbar->buttonCreate->click();
        $modal = new WidgetSettingsModal($this);
        $modal->waitUntilOpened();

        $title = $this->random->name(12);
        $print_message = $this->random->name(12);
        $code = $this->getJavascriptPrintCode($print_message);

        $modal->form->title->fill($title);
        $modal->form->code->fill($code);
        $modal->form->saveButton->click();
        $this->waitUntilTextIsPresent('This is what the widget will look like when added to a page.');
        $modal->close();
        $modal->waitUntilClosed();

        // Get the created widget from the table.
        $widgets = $this->configurePage->widgetTable->rows;
        $widget = end($widgets);

        // Keep the wid for later use.
        $wid = $widget->wid;

        // Switch to chief editor.
        $this->userSessionService->switchUser('ChiefEditor');

        // Create a landing page.
        $nid = $this->contentCreationService->createLandingPage();
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();

        // Create a widget pane and select the widget we just created.
        $content_type = new EmbedWidgetPanelsContentType($this);

        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');

        $modal = new AddPaneModal($this);
        $modal->selectContentType($content_type);

        $content_type->widget = $wid;
        $content_type->fillInConfigurationForm();

        $modal->submit();
        $modal->waitUntilClosed();

        // Verify that the widget code is replaced with placeholder text in the
        // IPE editor.
        $this->waitUntilTextIsPresent('This will render "' . $title . '" widget on the front-end.');

        // Publish the page, go to the front end, and verify that the widget is
        // also present there.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();
        $this->adminViewPage->contextualToolbar->buttonPublish->click();
        $this->adminViewPage->checkArrival();
        // Verify that the widget code is replaced with placeholder text in the
        // admin node view.
        $this->assertTextPresent('This will render "' . $title . '" widget on the front-end.');

        $this->adminViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->viewPage->checkArrival();
        $this->assertTextPresent($print_message);

        // Switch back to site manager, and delete the widget.
        $this->userSessionService->switchUser('SiteManager');
        $this->configurePage->go();
        $widget = $this->configurePage->widgetTable->getRowByWid($wid);
        $widget->linkDelete->click();
        $modal = new WidgetDeleteModal($this);
        $modal->waitUntilOpened();
        $modal->buttonConfirm->click();
        $modal->waitUntilClosed();

        // Switch back to chief editor.
        $this->userSessionService->switchUser('ChiefEditor');

        // Verify that the widget displays a message on the back end asking the
        // user to reconfigure or remove the pane.
        $this->layoutPage->go($nid);
        $this->assertTextPresent('The widget this pane was using has been removed. Please remove or reconfigure this pane.');
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        // Verify that the message for the editor is not visible on the front
        // end.
        $this->adminViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->viewPage->checkArrival();
        $this->assertTextNotPresent('The widget this pane was using has been removed. Please remove or reconfigure this pane.');
    }

    /**
     * Provides a functional javascript to mimmick widgets.
     *
     * The javascript will print a given message.
     *
     * @param string $message
     *   A message to print.
     *
     * @return string
     *   Javascript code.
     */
    protected function getJavascriptPrintCode($message)
    {
        // Don't use document.write() as it will overwrite all html on the page.
        $id = 'javascript-' . $this->alphanumericTestDataProvider->getValidValue(12);
        $code = '<div id="' . $id . '"></div>';
        $code .= '<script type="text/javascript">';
        $code .= 'jQuery("#' . $id . '").html("' . $message .'");';
        $code .= '</script>';
        return $code;
    }
}
