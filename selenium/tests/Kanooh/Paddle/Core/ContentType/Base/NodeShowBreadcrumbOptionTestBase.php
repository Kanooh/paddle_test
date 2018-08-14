<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\Base\NodeShowBreadcrumbOptionTestBase.
 */

namespace Kanooh\Paddle\Core\ContentType\Base;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerAddPage\ThemerAddPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ThemerEditPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as NodeFrontPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Test the show breadcrumb option.
 */
abstract class NodeShowBreadcrumbOptionTestBase extends WebDriverTestCase
{
    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The 'Overview' page of the Paddle Themer module.
     *
     * @var ThemerOverviewPage
     */
    protected $themerOverviewPage;

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
     * Node edit page.
     *
     * @var EditPage
     */
    protected $editPage;

    /**
     * Node view page.
     *
     * @var ViewPage
     */
    protected $viewPage;

    /**
     * Node front end view page.
     *
     * @var NodeFrontPage
     */
    protected $frontPage;

    /**
     * The 'Add' page of the Paddle Content Manager module.
     *
     * @var AddPage
     */
    protected $addContentPage;

    /**
     * @var Random
     */
    protected $random;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * Creates a node of the content type that is being tested.
     *
     * @param string $title
     *   Optional title for the node. If omitted a random title will be used.
     *
     * @return int
     *   The node ID of the node that was created.
     */
    abstract public function setupNode($title = null);

    /**
     * Get the machine name of the content type.
     *
     * @return string
     *   The machine name of the content type.
     */
    abstract protected function getContentTypeName();

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->themerOverviewPage = new ThemerOverviewPage($this);
        $this->themerAddPage = new ThemerAddPage($this);
        $this->themerEditPage = new ThemerEditPage($this);
        $this->editPage = new EditPage($this);
        $this->addContentPage = new AddPage($this);
        $this->viewPage = new ViewPage($this);
        $this->frontPage = new NodeFrontPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->random = new Random();
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->contentCreationService->cleanUp($this);
        parent::tearDown();
    }

    /**
     * Tests the "show breadcrumbs" options.
     *
     * @group contentType
     * @group breadcrumbs
     * @group editing
     * @group nodeShowBreadcrumbOptionTestBase
     * @group themer
     */
    public function testShowBreadcrumbSetting()
    {
        $this->userSessionService->login('SiteManager');

        // Go to the themer overview page and create a new theme.
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
        $theme_name = $this->themerEditPage->getThemeName();

        // Unfold the body section.
        $this->themerEditPage->body->header->click();

        // Check if the show breadcrumb checkbox is enabled by default.
        $checkbox = $this->themerEditPage->body->getShowBreadcrumbTrailCheckboxByContentType($this->getContentTypeName());
        $this->assertTrue($checkbox->isChecked());

        // Save the theme.
        $this->moveto($this->themerEditPage->buttonSubmit);
        $this->themerEditPage->buttonSubmit->click();

        // Enable the theme.
        $this->themerOverviewPage->checkArrival();
        $theme = $this->themerOverviewPage->theme($theme_name);
        $this->assertEquals($human_theme_name, $theme->title->text());

        $theme->enable->click();
        $this->themerOverviewPage->checkArrival();
        $this->clearActiveThemeCache($theme_name);

        // Setup the node.
        $nid = $this->setupNode();

        // Check if the node has the breadcrumb checkbox checked when editing.
        $this->editPage->go($nid);
        $this->assertTrue($this->editPage->showBreadcrumbCheckbox->selected());
        $this->editPage->contextualToolbar->buttonBack->click();
        $this->viewPage->checkArrival();

        // Go to the front end view of the page and check if the breadcrumb is shown.
        $this->viewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontPage->checkArrival();
        $elements = $this->viewPage->getElementCountByXPath('//div[@id="breadcrumb"]');
        $this->assertEquals(1, $elements);
        $this->assertTextNotPresent('Show breadcrumb');

        // Set the breadcrumb checkbox to false.
        $this->editPage->go(array($nid));
        $this->assertTrue($this->editPage->showBreadcrumbCheckbox->selected());
        $this->editPage->showBreadcrumbCheckbox->click();
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->viewPage->checkArrival();

        // Verify in the front end that no breadcrumb is shown.
        $this->viewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontPage->checkArrival();
        $elements = $this->viewPage->getElementCountByXPath('//div[@id="breadcrumb"]');
        $this->assertEquals(0, $elements);
        $this->assertTextNotPresent('Show breadcrumb');

        // Now we run the tests for when the global setting is unchecked.
        $this->themerEditPage->go($theme_name);

        // Unfold the body section.
        $this->themerEditPage->body->header->click();

        // Set the show breadcrumb checkbox to unchecked.
        $checkbox = $this->themerEditPage->body->getShowBreadcrumbTrailCheckboxByContentType($this->getContentTypeName());
        $this->assertTrue($checkbox->isChecked());
        $checkbox->uncheck();
        $this->assertFalse($checkbox->isChecked());
        $this->moveto($this->themerEditPage->buttonSubmit);
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();
        $this->clearActiveThemeCache($theme_name);

        // Setup the node.
        $nid = $this->setupNode();

        // Check if the node has the breadcrumb checkbox unchecked when editing.
        $this->editPage->go($nid);
        $this->assertFalse($this->editPage->showBreadcrumbCheckbox->selected());
        $this->editPage->contextualToolbar->buttonBack->click();
        $this->viewPage->checkArrival();

        // Go to the front end view of the page and check if the breadcrumb is shown.
        $this->viewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontPage->checkArrival();
        $elements = $this->viewPage->getElementCountByXPath('//div[@id="breadcrumb"]');
        $this->assertEquals(0, $elements);

        // Set the breadcrumb checkbox to true.
        $this->editPage->go(array($nid));
        $this->assertFalse($this->editPage->showBreadcrumbCheckbox->selected());
        $this->editPage->showBreadcrumbCheckbox->click();
        $this->assertTrue($this->editPage->showBreadcrumbCheckbox->selected());
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->viewPage->checkArrival();

        // Verify in the front end that the breadcrumb is shown.
        $this->viewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontPage->checkArrival();
        $elements = $this->viewPage->getElementCountByXPath('//div[@id="breadcrumb"]');
        $this->assertEquals(1, $elements);
    }

    /**
     *  If you made changes to the active theme configuration during the test
     *  you need to clear the ctools object cache.
     *
     *  @param string $name
     *    The name of the active theme.
     */
    public function clearActiveThemeCache($name)
    {
        variable_set('paddle_theme', $name);
        ctools_export_load_object_reset();
    }
}
