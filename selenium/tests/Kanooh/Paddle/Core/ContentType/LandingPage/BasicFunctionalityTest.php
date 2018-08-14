<?php

/**
 * @file
 * Contains \Kanooh\Paddle\BasicFunctionalityTest.
 */

namespace Kanooh\Paddle\Core\ContentType\LandingPage;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\LandingPageViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests for basic functionality of landing pages.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class BasicFunctionalityTest extends WebDriverTestCase
{

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

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
     * The random data generation class.
     *
     * @var Random
     */
    protected $random;

    /**
     * The panels content edit page of a landing page.
     *
     * @var PanelsContentPage
     */
    protected $panelsContentPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Instantiate the Pages that will be visited in the test.
        $this->userSessionService = new UserSessionService($this);
        $this->addContentPage = new AddPage($this);
        $this->panelsContentPage = new PanelsContentPage($this);
        $this->administrativeNodeViewPage = new LandingPageViewPage($this);
        $this->random = new Random();

        // Go to the login page and log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
    }


    public function tearDown()
    {
        // Log out.
        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->logout();

        parent::tearDown();
    }

    /**
     * Test if the "Cancel" button works on Page Layout.
     *
     * It should redirect to the admin view.
     *
     * @group workflow
     */
    public function testCancelButtonOnPageLayout()
    {
        // Create new landing page.
        $nid = $this->contentCreationService->createLandingPage();

        // After creating the page we are redirected to the administrative node
        // view. Click "Cancel" on the Page Layout page.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageLayout->click();
        $this->panelsContentPage->checkArrival();
        $this->panelsContentPage->contextualToolbar->buttonBack->click();
        // Click confirm on the alert box that we want to leave the current
        // page without making changes.
        $this->acceptAlert();

        // Verify we're on the admin view of the node we just created.
        $this->administrativeNodeViewPage->checkArrival();
        $path_arguments = $this->administrativeNodeViewPage->getPathArguments();
        $this->assertEquals($nid, $path_arguments[0]);
    }

    /**
     * Test if the "Change layout" functionality works properly.
     *
     * The change is applied and it is visible immediately.
     *
     * @group panes
     */
    public function testChangeLayout()
    {
        // Create new landing page.
        $this->addContentPage->go();
        $current_layout = 'paddle_2_col_9_3';
        // Generate a second(different) layout.
        do {
            $second_layout = 'paddle_chi';
        } while ($second_layout == $current_layout);

        $nid = $this->contentCreationService->createLandingPage($current_layout);

        // After creating the page we should be redirected to the
        // administrative node view.
        // Change the layout.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageLayout->click();
        $this->panelsContentPage->checkArrival();
        $this->panelsContentPage->changeLayout($second_layout);

        // Check that the correct layout is displayed.
        $ipe_placeholders_xpath = '//div[contains(@class, "panels-ipe-display-container")]' .
            '//div[contains(@class, "panels-ipe-placeholder")]';
        $this->waitUntilElementIsDisplayed($ipe_placeholders_xpath);
        $ipe_placeholders = $this->elements($this->using('xpath')->value($ipe_placeholders_xpath));

        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element $ipe_placeholder */
        foreach ($ipe_placeholders as $ipe_placeholder) {
            $this->assertTrue($ipe_placeholder->displayed());
        }

        // @todo - Check that the text is not present: "Reload the page to get
        //   source for" ... This text appears when the panes are is not
        //   refreshed after "Change layout". Since we want to verify they are
        //   refreshed we check that the text doesn't exist.
        // Check if there is a pane placeholders displayed (the headers
        // indicating regions and buttons like the "Add new pane" button).
        $ipe_containers_xpath = '//div[contains(@class, "panels-ipe-display-container")]' .
            '//div[contains(@class, "paddle-layout-' . $second_layout . '")]';
        $this->waitUntilElementIsDisplayed($ipe_containers_xpath);
        $ipe_containers = $this->elements($this->using('xpath')->value($ipe_containers_xpath));

        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element $ipe_container */
        foreach ($ipe_containers as $ipe_container) {
            $this->assertTrue($ipe_container->displayed());
        }

        // Save the page so that subsequent tests are not greeted by an alert.
        $this->panelsContentPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
    }

    /**
     * Tests that after clicking the browser "Back" button, the correct content is still shown.
     *
     * @group regression
     */
    public function testBackAfterAddingPanes()
    {
        // Create new landing page.
        $this->contentCreationService->createLandingPage();

        // After creating the page we should be redirected to the administrative node view.
        // Add a pane to a region.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageLayout->click();
        $this->panelsContentPage->checkArrival();

        $region = $this->panelsContentPage->display->getRandomRegion();
        $custom_content_pane = new CustomContentPanelsContentType($this);
        $test_string = $this->random->name(64);
        $custom_content_pane->body = $test_string;
        $pane = $region->addPane($custom_content_pane);
        $this->panelsContentPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The page has been updated.');

        // Check if we're being redirected correctly.
        $this->administrativeNodeViewPage->checkArrival();
        $this->assertTextPresent($test_string);

        // Click the "Back" button and verify the correct content is still shown.
        $this->administrativeNodeViewPage->goBack();
        $this->panelsContentPage->checkArrival();
        $this->assertTextPresent($test_string);

        // Save the page so that subsequent tests are not greeted by an alert.
        $this->panelsContentPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
    }
}
