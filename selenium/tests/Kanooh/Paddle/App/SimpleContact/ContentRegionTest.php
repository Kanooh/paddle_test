<?php

/**
 * Contains \Kanooh\Paddle\App\SimpleContact\ContentRegionTest.
 */

namespace Kanooh\Paddle\App\SimpleContact;

use Kanooh\Paddle\Apps\SimpleContact;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Entity\PanelsContentPage\PanelsContentPage as ContentRegionPanelsContentPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\LandingPageViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage as LandingPagePanelsContentPage;
use Kanooh\Paddle\Pages\Admin\DashboardPage\DashboardPage;
use Kanooh\Paddle\Pages\Admin\Structure\ContentRegionPage\ContentRegionPage;
use Kanooh\Paddle\Pages\Admin\Structure\ContentRegionPage\ContentRegionUtility;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Pages\Node\EditPage\EditSimpleContactPagePage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the content regions for the simple contact paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ContentRegionTest extends WebDriverTestCase
{
    /**
     * @var AddPage
     */
    protected $addContentPage;

    /**
     * @var LandingPageViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var CleanUpService
     */
    protected $cleanUpService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var ContentRegionPage
     */
    protected $contentRegionConfigurationPage;

    /**
     * @var ContentRegionPanelsContentPage
     */
    protected $contentRegionPanelsPage;

    /**
     * @var ContentRegionUtility
     */
    protected $contentRegionUtility;

    /**
     * @var DashboardPage
     */
    protected $dashboardPage;

    /**
     * @var EditSimpleContactPagePage
     */
    protected $editSimpleContactPage;

    /**
     * @var ViewPage
     */
    protected $frontendNodeViewPage;

    /**
     * @var LandingPagePanelsContentPage
     */
    protected $landingPageLayoutPage;

    /**
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * @var array
     */
    protected $testContent;

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

        // Create some instances to use later on.
        $this->addContentPage = new AddPage($this);
        $this->administrativeNodeViewPage = new LandingPageViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->cleanUpService = new CleanUpService($this);
        $this->contentRegionConfigurationPage = new ContentRegionPage($this);
        $this->contentRegionPanelsPage = new ContentRegionPanelsContentPage($this);
        $this->contentRegionUtility = new ContentRegionUtility($this);
        $this->dashboardPage = new DashboardPage($this);
        $this->editSimpleContactPage = new EditSimpleContactPagePage($this);
        $this->frontendNodeViewPage = new ViewPage($this);
        $this->landingPageLayoutPage = new LandingPagePanelsContentPage($this);
        $this->layoutPage = new LayoutPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Enable the app we are testing.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new SimpleContact);

        // Set up test data.
        $this->testContent['all_pages']['right'] = $this->alphanumericTestDataProvider->getValidValue(64);
        $this->testContent['all_pages']['bottom'] = $this->alphanumericTestDataProvider->getValidValue(64);
        $this->testContent['simple_contact_page']['right'] = $this->alphanumericTestDataProvider->getValidValue(64);
        $this->testContent['simple_contact_page']['bottom'] = $this->alphanumericTestDataProvider->getValidValue(64);

        // Go to the login page and log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Tests the addition of Ctools content type "Content Region" to Landing pages
     * panes.
     *
     * @group panes
     */
    public function testAddContentRegionPane()
    {
        // Start by cleaning up the entities.
        $this->cleanUpService->deleteEntities('paddle_content_region');
        $this->contentRegionConfigurationPage->go();

        // Add custom content panes to the 'simple contact pages' content region display.
        $this->contentRegionUtility->addCustomContentPanes(
            $this->contentRegionConfigurationPage->getOverride('simple_contact_page')->editLink,
            $this->testContent['simple_contact_page']['right'],
            $this->testContent['simple_contact_page']['bottom']
        );

        // Create a new landing page. Continue on to the page layout.
        $this->addContentPage->go();
        $lp_nid = $this->contentCreationService->createLandingPage();

        // Test all combinations of content types and regions.
        foreach (array('right', 'bottom') as $region) {
            $this->landingPageLayoutPage->go($lp_nid);
            $pane = $this->contentRegionUtility->addContentRegionPane(
                'simple_contact_page',
                $region,
                $this->landingPageLayoutPage->display
            );
            // Check that the test content is visible.
            $this->assertTextPresent($this->testContent['simple_contact_page'][$region]);

            // Save the page, so that the following test is not confronted with an
            // alert box.
            $this->landingPageLayoutPage->contextualToolbar->buttonSave->click();
            $this->administrativeNodeViewPage->checkArrival();
        }
    }

    /**
     * Tests the content region panes on simple contact pages.
     *
     * @see https://one-agency.atlassian.net/browse/KANWEBS-1469
     *
     * @group panes
     */
    public function testContentRegionsOnSimpleContactPage()
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

        // Override the setting for the simple contact pages.
        $this->contentRegionConfigurationPage->getOverride('simple_contact_page')->enable();
        $this->contentRegionConfigurationPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved');
        $checkbox = $this->contentRegionConfigurationPage->getOverride('simple_contact_page')->checkbox;
        $this->assertTrue($checkbox->selected());

        // Click 'Edit content for every simple contact page' and add two panes: one to
        // the right region, and one to the bottom region.
        $this->contentRegionUtility->addCustomContentPanes(
            $this->contentRegionConfigurationPage->getOverride('simple_contact_page')->editLink,
            $this->testContent['simple_contact_page']['right'],
            $this->testContent['simple_contact_page']['bottom']
        );

        // Create a simple contact page. We end up on the administrative node view.
        $nid = $this->contentCreationService->createSimpleContact();

        // Go to the front-end view and check that the chosen panes for the simple
        // contact page content regions are shown.
        $this->frontendNodeViewPage->go($nid);
        $this->assertTextPresent($this->testContent['simple_contact_page']['right']);
        $this->assertTextPresent($this->testContent['simple_contact_page']['bottom']);

        // Go to the 'Page layout'.
        $this->layoutPage->go($nid);

        // Add two custom content panes, one to the right region, and one to the
        // bottom region.
        $custom_pane_content = array(
            'right' => $this->alphanumericTestDataProvider->getValidValue(64),
            'bottom' => $this->alphanumericTestDataProvider->getValidValue(64),
        );
        $regions = $this->layoutPage->display->getRegions();
        $custom_content_pane = new CustomContentPanelsContentType($this);

        // Add a custom content pane to the right region.
        $custom_content_pane->body = $custom_pane_content['right'];
        $regions['right']->addPane($custom_content_pane);

        // Add a custom content pane to the bottom region.
        $custom_content_pane->body = $custom_pane_content['bottom'];
        $regions['bottom']->addPane($custom_content_pane);

        // Save the page. We arrive on the administrative node view.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->assertTextPresent('The changes have been saved.');

        // Go to the frontend view and check that the new panes are shown in
        // addition to the chosen panes for the simple contact page content
        // regions.
        $this->frontendNodeViewPage->go($nid);
        $this->assertTextPresent($this->testContent['simple_contact_page']['right']);
        $this->assertTextPresent($this->testContent['simple_contact_page']['bottom']);
        $this->assertTextPresent($custom_pane_content['right']);
        $this->assertTextPresent($custom_pane_content['bottom']);

        // Go back to the global content region configuration page.
        $this->contentRegionConfigurationPage->go();

        // Set the checkbox to use global content settings and click 'Save.
        $this->contentRegionConfigurationPage->getOverride('simple_contact_page')->disable();
        $this->contentRegionConfigurationPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved');
        $checkbox = $this->contentRegionConfigurationPage->getOverride('simple_contact_page')->checkbox;
        $this->assertFalse($checkbox->selected());

        // Go to the front page of the simple contact page. Check that the global panes
        // are now shown.
        $this->frontendNodeViewPage->go($nid);
        $this->assertTextPresent($this->testContent['all_pages']['right']);
        $this->assertTextPresent($this->testContent['all_pages']['bottom']);

        // Check that the custom panes are still shown.
        $this->assertTextPresent($custom_pane_content['right']);
        $this->assertTextPresent($custom_pane_content['bottom']);

        // Check that the simple contact page panes are not shown.
        $this->assertTextNotPresent($this->testContent['simple_contact_page']['right']);
        $this->assertTextNotPresent($this->testContent['simple_contact_page']['bottom']);
    }

    /**
     * Tests the presence of the help text on the "Add Simple Contact" page.
     *
     * @see https://one-agency.atlassian.net/browse/KANWEBS-1544
     *
     * @group editing
     */
    public function testHelpTextPresenceNodeEdit()
    {
        $help_text = 'If you want to change the default settings of this contact form only, you can do this here.';

        $nid = $this->contentCreationService->createSimpleContact();
        $this->editSimpleContactPage->go($nid);
        $this->assertTextNotPresent($help_text);
        $this->editSimpleContactPage->labelOptionsLink->click();
        $this->waitUntilTextIsPresent($help_text);
        $this->assertTextPresent($help_text);

        // Save page, to prevent alert boxes from popping up after this.
        $this->editSimpleContactPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
    }
}
