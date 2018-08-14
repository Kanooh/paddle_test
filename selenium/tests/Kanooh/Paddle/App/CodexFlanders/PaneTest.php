<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\CodexFlanders\PaneTest.
 */

namespace Kanooh\Paddle\App\CodexFlanders;

use Kanooh\Paddle\Apps\CodexFlanders;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CodexFlanders\CodexFlandersPanelsContentType;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalApi\DrupalAjaxApi;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the Codex Flanders pane.
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
     * Basic page layout page.
     *
     * @var LayoutPage
     */
    protected $layoutPage;

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
        $this->adminViewPage = new AdminViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->layoutPage = new LayoutPage($this);

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new CodexFlanders);
    }

    /**
     * Tests the basic configuration of the Codex Flanders pane.
     *
     * @group panes
     * @group codexFlanders
     */
    public function testPaneConfiguration()
    {
        $nid = $this->contentCreationService->createBasicPage();

        // Add a codex Flanders pane to the page layout of the basic page.
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();

        $content_type = new CodexFlandersPanelsContentType($this);
        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');

        $modal = new AddPaneModal($this);
        $modal->selectContentType($content_type);

        // Fill out the form correctly and add another codex.
        $content_type->getForm()->url->fill('http://' . $this->alphanumericTestDataProvider->getValidValue() . '.com?AID=125645');

        $content_type->getForm()->removeCodexByName('remove_codex_1');
        $this->assertEmpty($content_type->getForm()->url->getContent());

        $content_type->getForm()->url->fill('http://' . $this->alphanumericTestDataProvider->getValidValue() . '.com?AID=125645');
        $content_type->getForm()->addAnotherCodex->click();
        $drupal_ajax_api = new DrupalAjaxApi($this);
        $drupal_ajax_api->waitUntilElementFinishedAjaxing($content_type->getForm()->addAnotherCodex);

        $this->assertNotEmpty($this->byName('codices[2][url]'));
        $this->assertNotEmpty($this->byName('codices[2][name]'));

        $content_type->getForm()->removeCodexByName('remove_codex_1');
        try {
            $content_type->getForm()->name;
            $this->fail('The first element should no longer be available.');
        } catch (\Exception $e) {
            // Do nothing.
        }

        $modal->close();
        $modal->waitUntilClosed();
        $this->layoutPage->contextualToolbar->buttonBack->click();
        $this->acceptAlert();
        $this->adminViewPage->checkArrival();
    }
}
