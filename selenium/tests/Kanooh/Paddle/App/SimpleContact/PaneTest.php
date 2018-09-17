<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\SimpleContact\PaneTest.
 */

namespace Kanooh\Paddle\App\SimpleContact;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Apps\SimpleContact;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\LandingPageViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Element\PanelsContentType\SimpleContactFormPanelsContentType;
use Kanooh\Paddle\Pages\Element\Region\Region;
use Kanooh\Paddle\Pages\Element\SimpleContactForm\SimpleContactForm as SimpleContactFormElement;
use Kanooh\Paddle\Pages\Node\EditPage\EditSimpleContactPagePage;
use Kanooh\Paddle\Pages\Node\EditPage\EditSimpleContactPagePageRandomFiller;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class PaneTest
 *
 * @package Kanooh\Paddle\App\SimpleContact
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneTest extends WebDriverTestCase
{
    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * The "add content" page.
     *
     * @var AddPage
     */
    protected $addPage;

    /**
     * The service to create content of several types.
     *
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * Admin view page for various nodes.
     *
     * @var ViewPage
     */
    protected $viewPage;

    /**
     * Panels content page for landing pages.
     *
     * @var PanelsContentPage
     */
    protected $panelsContentPage;

    /**
     * Layout page for various nodes.
     *
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * Random generator for strings.
     *
     * @var Random
     */
    protected $random;

    /**
     * The nid of the Simple Contact Page.
     *
     * @var int
     */
    protected $simpleContactPageNid;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * {@inheritdoc}
     */
    public function setupPage()
    {
        parent::setupPage();

        $this->addPage = new AddPage($this);

        $this->viewPage = new ViewPage($this);
        $this->panelsContentPage = new PanelsContentPage($this);
        $this->layoutPage = new LayoutPage($this);

        $this->random = new Random();

        // Go to the login page and log in as chief editor.
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->userSessionService->login('ChiefEditor');

        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new SimpleContact);

        $this->simpleContactPageNid = $this->contentCreationService->createSimpleContact();

        $this->userSessionService->logout();
    }

    /**
     * Tests that the Simple Contact Form can be added to landing pages.
     *
     * @dataProvider users
     *
     * @group panes
     */
    public function testLandingPage($user)
    {
        $this->userSessionService->login($user);

        // Using createAlfaLandingPage instead of createRandomLandingPage
        // reduces chances of random failures.
        $this->contentCreationService->createLandingPage();

        // Go to the landing page's panels content page.
        $view_page = new LandingPageViewPage($this);
        $view_page->contextualToolbar->buttonPageLayout->click();

        // Get the "right" region. We can always use the same region because
        // we don't have a random layout.
        $this->panelsContentPage->checkArrival();
        $regions = $this->panelsContentPage->display->getRegions();
        $region = $regions['right'];

        // Create the pane and add it to the region.
        $this->addSimpleContactPaneToRegion($region);

        // Save the page, and wait until the admin view is completely loaded.
        $this->panelsContentPage->contextualToolbar->buttonSave->click();
        $view_page->waitUntilPageIsLoaded();

        // Make sure the Simple Contact Form is present on the admin view page.
        $this->assertSimpleContactFormPresent($this->simpleContactPageNid);

        $view_page->contextualToolbar->buttonPreviewRevision->click();

        $front_end = new FrontEndViewPage($this);
        $front_end->checkArrival();
        $this->assertSimpleContactFormEnabled($this->simpleContactPageNid);
    }

    /**
     * Tests that the Simple Contact Form can be added to basic pages.
     *
     * @dataProvider users
     *
     * @group modals
     * @group panes
     */
    public function testBasicPage($user)
    {
        $this->userSessionService->login($user);

        // Create a basic page.
        $nid = $this->contentCreationService->createBasicPage();

        // Get the bottom region.
        $this->layoutPage->go($nid);
        $regions = $this->layoutPage->display->getRegions();
        $region = $regions['bottom'];

        // Create the pane, and add it to the bottom region.
        $this->addSimpleContactPaneToRegion($region);

        // Save the page. The layout's panes don't show on the admin view of
        // basic pages, so we have to go back to the layout page.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->viewPage->checkArrival();
        // Check that the form is present and disabled on admin node view.
        $this->assertSimpleContactFormPresent($this->simpleContactPageNid);
        $this->assertSimpleContactFormDisabled($this->simpleContactPageNid);

        $this->viewPage->contextualToolbar->buttonPageLayout->click();
        $this->layoutPage->checkArrival();

        // Make sure the Simple Contact Form is present & disabled on the layout
        // page.
        $this->assertSimpleContactFormPresent($this->simpleContactPageNid);
        $this->assertSimpleContactFormDisabled($this->simpleContactPageNid);

        $this->layoutPage->contextualToolbar->buttonBack->click();
        $this->acceptAlert();

        $this->viewPage->checkArrival();
        $this->viewPage->contextualToolbar->buttonPreviewRevision->click();

        $front_end = new FrontEndViewPage($this);
        $front_end->checkArrival();
        $this->assertSimpleContactFormEnabled($this->simpleContactPageNid);
    }

    /**
     * Dataprovider for tests that should be run on multiple user roles.
     */
    public function users()
    {
        return array(
            array('Editor'),
            array('ChiefEditor'),
        );
    }

    /**
     * Adds a Simple Contact Form pane to a specific region.
     *
     * @param Region $region
     *   Region to add the Simple Contact Form pane to.
     */
    public function addSimpleContactPaneToRegion(Region $region)
    {
        $pane_type = new SimpleContactFormPanelsContentType($this);
        $pane_type->simpleContactPageNid = $this->simpleContactPageNid;
        $pane = $region->addPane($pane_type);
        $pane->edit($pane_type);
        $pane->editPaneModal->waitUntilClosed();
    }

    /**
     * Asserts that the Simple Contact Form pane is rendered on the page.
     */
    public function assertSimpleContactFormPresent($simple_contact_nid, $message = '')
    {
        $form = new SimpleContactFormElement($this, $simple_contact_nid);
        $this->assertNotNull($form, $message);
    }

    /**
     * Asserts that the Simple Contact Form is disabled.
     */
    public function assertSimpleContactFormDisabled($simple_contact_nid, $message = '')
    {
        $form = new SimpleContactFormElement($this, $simple_contact_nid);
        $disabled = (bool) $form->submitButton()->attribute('disabled');
        $this->assertTrue($disabled, $message);
    }

    /**
     * Asserts that the Simple Contact Form is enabled.
     */
    public function assertSimpleContactFormEnabled($simple_contact_nid, $message = '')
    {
        $form = new SimpleContactFormElement($this, $simple_contact_nid);
        $disabled = (bool) $form->submitButton()->attribute('disabled');
        $this->assertFalse($disabled, $message);
    }

    /**
     * Tear down method.
     */
    public function tearDown()
    {
        // Log out.
        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->logout();

        parent::tearDown();
    }
}
