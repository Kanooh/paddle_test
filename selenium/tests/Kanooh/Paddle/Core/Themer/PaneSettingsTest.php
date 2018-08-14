<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Themer\DefaultColorPaletteTest.
 */

namespace Kanooh\Paddle\Core\Themer;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage as LayoutPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerAddPage\ThemerAddPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ThemerEditPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Pages\Element\Pane\CustomContentPane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Test the pane settings for the default theme.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneSettingsTest extends WebDriverTestCase
{

    /**
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * @var ThemerOverviewPage
     */
    protected $themerOverviewPage;

    /**
     * @var ThemerAddPage
     */
    protected $themerAddPage;

    /**
     * @var ThemerEditPage
     */
    protected $themerEditPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * @var ViewPage
     */
    protected $viewPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->layoutPage = new LayoutPage($this);
        $this->themerOverviewPage = new ThemerOverviewPage($this);
        $this->themerAddPage = new ThemerAddPage($this);
        $this->themerEditPage = new ThemerEditPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->viewPage = new ViewPage($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as Site manager.
        $this->userSessionService->login('SiteManager');
    }

    /**
     * Test setting of the pane top text which can be displayed as <h2>.
     *
     * @dataProvider themeNameDataProvider
     *
     * @param string $theme_name
     *   The machine name of the theme to test on.
     *
     * @group themer
     */
    public function testPaneTopAsH2Setting($theme_name)
    {
        // Create a landing page.
        $nid = $this->contentCreationService->createLandingPage();

        // Head to the layout page.
        $this->layoutPage->go($nid);

        // Add a pane with a pane top filled in.
        $region = $this->layoutPage->display->getRandomRegion();
        $content_type = new CustomContentPanelsContentType($this);
        $top_text = $this->alphanumericTestDataProvider->getValidValue();

        $callable = new SerializableClosure(
            function () use ($content_type, $top_text) {
                $content_type->topSection->enable->check();
                $content_type->topSection->contentTypeRadios->text->select();
                $content_type->topSection->text->fill($top_text);
            }
        );
        $pane = $region->addPane($content_type, $callable);
        $pane_uuid = $pane->getUuid();

        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Assert that the pane top text is inside a div.
        $this->viewPage->go($nid);

        $frontend_pane = new CustomContentPane($this, $pane_uuid);
        $frontend_pane->topSection->getElement()->byXPath('//div[normalize-space(text())="' . $top_text . '"]');

        // Create a new theme.
        $this->themerOverviewPage->go();
        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();
        $this->themerAddPage->baseTheme->selectOptionByValue($theme_name);
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();
        $theme = $this->themerEditPage->getThemeName();

        // Tick off the pane top as <h2> option.
        $this->themerEditPage->body->header->click();
        $this->themerEditPage->body->displayPaneTopAsH2->check();

        // Save and enable the theme.
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();
        $this->themerOverviewPage->theme($theme)->enable->click();
        $this->themerOverviewPage->checkArrival();

        // Head to the front-end of the layout page again.
        $this->viewPage->go($nid);

        // Assert that the pane top text is inside <h2> tags.
        $frontend_pane = new CustomContentPane($this, $pane_uuid);
        $frontend_pane->topSection->getElement()->byXPath('//h2[normalize-space(text())="' . $top_text . '"]');
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Deselect the show pane top as <h2> option so other tests won't break.
        $this->themerOverviewPage->go();
        $theme = $this->themerOverviewPage->getActiveTheme();
        $theme->edit->click();
        $this->themerEditPage->checkArrival();
        $this->themerEditPage->body->header->click();
        $this->themerEditPage->body->displayPaneTopAsH2->uncheck();
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();

        parent::tearDown();
    }

    /**
     * Data provider for the pane settings tests.
     */
    public function themeNameDataProvider()
    {
        return array(
            array('vo_standard'),
            array('kanooh_theme_v2'),
        );
    }
}
