<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentRegion\ContentRegionTest.
 */

namespace Kanooh\Paddle\Core\ContentRegion;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\LandingPageViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Entity\PanelsContentPage\PanelsContentPage
    as ContentRegionPanelsContentPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage as LandingPagePanelsContentPage;
use Kanooh\Paddle\Pages\Admin\DashboardPage\DashboardPage;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage\MenuOverviewPage;
use Kanooh\Paddle\Pages\Admin\Structure\ContentRegionPage\ContentRegionPage;
use Kanooh\Paddle\Pages\Admin\Structure\ContentRegionPage\ContentRegionUtility;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Pages\Element\PreviewToolbar\PreviewToolbar;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the interaction between content and the content regions.
 *
 * It tests the ctools content type "Content Region" and how the Content Regions
 * get displayed in the Basic pages.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaddleContentRegionTest extends WebDriverTestCase
{

    /**
     * The 'Add' page of the Paddle Content Manager module.
     *
     * @var AddPage
     */
    protected $addContentPage;

    /**
     * The administrative node view of a landing page.
     *
     * @var LandingPageViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The content region configuration page.
     *
     * @var ContentRegionPage
     */
    protected $contentRegionConfigurationPage;

    /**
     * The content region panels page for all pages.
     *
     * @var ContentRegionPanelsContentPage
     */
    protected $contentRegionPanelsPage;

    /**
     * The Dashboard page.
     *
     * @var DashboardPage
     */
    protected $dashboardPage;

    /**
     * The front-end view of a node.
     *
     * @var ViewPage
     */
    protected $frontendNodeViewPage;

    /**
     * The panels display of a landing page.
     *
     * @var LandingPagePanelsContentPage
     */
    protected $landingPageLayoutPage;

    /**
     * The panels display of a basic page.
     *
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * The menu overview page.
     *
     * @var MenuOverviewPage
     */
    protected $menuOverviewPage;

    /**
     * The random data generation class.
     *
     * @var Random $random
     */
    protected $random;

    /**
     * Test content
     *
     * @var array
     */
    protected $testContent;

    /**
     * The utility class for common function for content regions.
     *
     * @var ContentRegionUtility
     */
    protected $contentRegionUtility;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->addContentPage = new AddPage($this);
        $this->administrativeNodeViewPage = new LandingPageViewPage($this);
        $this->contentRegionConfigurationPage = new ContentRegionPage($this);
        $this->contentRegionPanelsPage = new ContentRegionPanelsContentPage($this);
        $this->dashboardPage = new DashboardPage($this);
        $this->frontendNodeViewPage = new ViewPage($this);
        $this->landingPageLayoutPage = new LandingPagePanelsContentPage($this);
        $this->layoutPage = new LayoutPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->menuOverviewPage = new MenuOverviewPage($this);
        $this->random = new Random();
        $this->contentRegionUtility = new ContentRegionUtility($this);

        // Set up test data.
        $this->testContent['all_pages']['right'] = $this->random->name(64);
        $this->testContent['all_pages']['bottom'] = $this->random->name(64);
        $this->testContent['basic_page']['right'] = $this->random->name(64);
        $this->testContent['basic_page']['bottom'] = $this->random->name(64);

        // Go to the login page and log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
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
     * Tests the addition of Ctools content type "Content Region" to Landing pages
     * panes.
     *
     * @group panes
     */
    public function testAddContentRegionPane()
    {
        // Navigate to the Content Regions configuration page through the admin
        // menu, starting at the dashboard.
        $this->dashboardPage->go();
        $this->dashboardPage->adminMenuLinks->linkStructure->click();

        // We arrive on the menu overview page.
        $this->menuOverviewPage->checkArrival();
        $this->menuOverviewPage->adminMenuLinks->linkRegions->click();

        // We arrive on the content region configuration page.
        $this->contentRegionConfigurationPage->checkArrival();

        // Add custom content panes to the global content region display.
        $this->contentRegionUtility->addCustomContentPanes(
            $this->contentRegionConfigurationPage->links->linkEditContentForAllPages,
            $this->testContent['all_pages']['right'],
            $this->testContent['all_pages']['bottom']
        );

        // Add custom content panes to the 'basic pages' content region display.
        $this->contentRegionUtility->addCustomContentPanes(
            $this->contentRegionConfigurationPage->getOverride('basic_page')->editLink,
            $this->testContent['basic_page']['right'],
            $this->testContent['basic_page']['bottom']
        );

        // Create a new landing page. Continue on to the page layout.
        $nid = $this->contentCreationService->createLandingPage();
        $this->landingPageLayoutPage->go($nid);

        // Test all combinations of content types and regions.
        foreach (array('all_pages', 'basic_page') as $type) {
            foreach (array('right', 'bottom') as $region) {
                // Make sure the editor is fully loaded.
                $this->landingPageLayoutPage->waitUntilPageIsLoaded();
                $pane = $this->contentRegionUtility->addContentRegionPane(
                    $type,
                    $region,
                    $this->landingPageLayoutPage->display
                );
                // Check that the test content is visible.
                $this->assertTextPresent($this->testContent[$type][$region]);
                // Remove the pane.
                $pane->delete();
            }
        }
        // Save the page, so that the following test is not confronted with an
        // alert box.
        $this->landingPageLayoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
    }

    /**
     * Tests the content region panes on basic pages.
     *
     * @group panes
     */
    public function testContentRegionsOnBasicPage()
    {
        // Go to the content region configuration page.
        $this->contentRegionConfigurationPage->go();

        // Click 'Edit content for all pages' and add two panes: one to the
        // right region, and one to the bottom region.
        $this->contentRegionUtility->addCustomContentPanes(
            $this->contentRegionConfigurationPage->links->linkEditContentForAllPages,
            $this->testContent['all_pages']['right'],
            $this->testContent['all_pages']['bottom']
        );

        // Override the setting for the basic pages.
        $this->contentRegionConfigurationPage->getOverride('basic_page')->enable();
        $this->contentRegionConfigurationPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved');
        $checkbox = $this->contentRegionConfigurationPage->getOverride('basic_page')->checkbox;
        $checked = $checkbox->selected();
        $this->assertTrue($checked);

        // Click 'Edit content for every basic page' and add two panes: one to
        // the right region, and one to the bottom region.
        $this->contentRegionUtility->addCustomContentPanes(
            $this->contentRegionConfigurationPage->getOverride('basic_page')->editLink,
            $this->testContent['basic_page']['right'],
            $this->testContent['basic_page']['bottom']
        );

        // Create a basic page. We end up on the administrative node view.
        $nid = $this->contentCreationService->createBasicPage();
        $this->administrativeNodeViewPage->go($nid);

        // Go to the front-end view and check that the chosen panes for the basic
        // page content regions are shown.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontendNodeViewPage->waitUntilPageIsLoaded();
        $this->assertTextPresent($this->testContent['basic_page']['right']);
        $this->assertTextPresent($this->testContent['basic_page']['bottom']);

        // Go back to the administrative node view and click on 'Page layout'.
        // The existence of preview toolbar on a ViewPage is not guaranteed
        // as anonymous users will not see it. Because of this we instantiate
        // it locally.
        $previewToolbar = new PreviewToolbar($this);
        $previewToolbar->closeButton()->click();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageLayout->click();
        $this->layoutPage->checkArrival();

        // Add two custom content panes, one to the right region, and one to the
        // bottom region.
        $custom_pane_content = array(
            'right' => $this->random->name(64),
            'bottom' => $this->random->name(64),
        );
        $regions = $this->layoutPage->display->getRegions();
        $custom_content_pane = new CustomContentPanelsContentType($this);

        // Add a custom content pane to the right region.
        $custom_content_pane->body = $custom_pane_content['right'];
        $regions['right']->addPane($custom_content_pane);

        // Add a custom content pane to the bottom region.
        $custom_content_pane->body = $custom_pane_content['bottom'];
        // Add it to the right region first.
        $pane = $regions['right']->addPane($custom_content_pane);
        // Try to move it to a region it shouldn't be able to be moved to.
        $locked_region = $this->layoutPage->display->getLockedRegion();
        try {
            $this->dragAndDrop($pane->toolbar->buttonDragHandle, $locked_region->dropZone());
            $this->assertTextNotPresent($custom_pane_content['bottom'], $locked_region->getWebdriverElement());
        } catch (\Exception $e) {
            // A locked region shouldn't have a drop zone and can't be dragged
            // into. So, it'll throw an error.
            $this->assertInstanceOf('Exception', $e);
        }
        // Drag and drop it to the bottom region.
        $this->dragAndDrop($pane->toolbar->buttonDragHandle, $regions['bottom']->dropZone());
        $this->assertTextPresent($custom_pane_content['bottom'], $regions['bottom']->getWebdriverElement());

        // Save the page. We arrive on the administrative node view.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->assertTextPresent('The changes have been saved.');

        // Go to the frontend view and check that the new panes are shown in
        // addition to the chosen panes for the basic page content regions.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontendNodeViewPage->waitUntilPageIsLoaded();
        $this->assertTextPresent($this->testContent['basic_page']['right']);
        $this->assertTextPresent($this->testContent['basic_page']['bottom']);
        $this->assertTextPresent($custom_pane_content['right']);
        $this->assertTextPresent($custom_pane_content['bottom']);

        // Go back to the global content region configuration page.
        $this->contentRegionConfigurationPage->go();

        // Set the checkbox to use global content settings and click 'Save.
        $this->contentRegionConfigurationPage->getOverride('basic_page')->disable();
        $this->contentRegionConfigurationPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved');
        $checkbox = $this->contentRegionConfigurationPage->getOverride('basic_page')->checkbox;
        $this->assertFalse($checkbox->selected());

        // Go to the front page of the basic page. Check that the global panes
        // are now shown.
        $this->administrativeNodeViewPage->go($nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontendNodeViewPage->waitUntilPageIsLoaded();
        $this->assertTextPresent($this->testContent['all_pages']['right']);
        $this->assertTextPresent($this->testContent['all_pages']['bottom']);

        // Check that the custom panes are still shown.
        $this->assertTextPresent($custom_pane_content['right']);
        $this->assertTextPresent($custom_pane_content['bottom']);

        // Check that the basic page panes are not shown.
        $this->assertTextNotPresent($this->testContent['basic_page']['right']);
        $this->assertTextNotPresent($this->testContent['basic_page']['bottom']);
    }

    /**
     * Drag and drop source to target.
     *
     * At the moment, this only works when both, source and target, are within
     * the viewport.
     *
     * @todo Update Selenium WebDriver once issue #3075 is in. Then, remove the
     * workarounds and move this function to a shared place, for other tests to
     * use.
     * @see https://code.google.com/p/selenium/issues/detail?id=3075#c33
     */
    public function dragAndDrop(
        \PHPUnit_Extensions_Selenium2TestCase_Element $source,
        \PHPUnit_Extensions_Selenium2TestCase_Element $target
    ) {
        // Workaround: enlarge the viewport as much as possible.
        $this->prepareSession()->currentWindow()->maximize();
        // Workaround: first move to target so there's more chance that the
        // target is visible when we want to move the source to it.
        $this->moveto($target);
        // Drag and drop.
        $this->moveto($source);
        $this->buttondown();
        $this->moveto($target);
        $this->buttonup();
    }
}
