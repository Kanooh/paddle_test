<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\PaddleImagePaneTest.
 */

namespace Kanooh\Paddle\Core\Pane;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\LandingPageViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Admin\DashboardPage\DashboardPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\Layout\Paddle2Col3to9Layout;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\Pane\ImagePane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ImagePanelsContentType;
use Kanooh\Paddle\Pages\Element\PreviewToolbar\PreviewToolbar;
use Kanooh\Paddle\Pages\Element\Scald\LibraryModal;
use Kanooh\Paddle\Pages\Node\ViewPage\LandingPageViewPage as FrontEndLandingPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalApi\DrupalAtomApi;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests functionalities specific to the Image pane.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ImagePaneTest extends WebDriverTestCase
{

    /**
     * The alphanumeric test data provider.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * The service to create content of several types.
     *
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * Test images.
     *
     * @var array
     */
    protected $images;

    /**
     * The front-end view of a landing page.
     *
     * @var FrontEndLandingPage
     */
    protected $frontendLandingPage;

    /**
     * The 'Add content' page of the Paddle Content Manager module.
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
     * The Dashboard page.
     *
     * @var DashboardPage
     */
    protected $dashboardPage;

    /**
     * The Drupal Atom API.
     *
     * @var DrupalAtomAPI
     */
    protected $drupalAtomApi;

    /**
     * The panels display of a landing page.
     *
     * @var PanelsContentPage
     */
    protected $landingPagePanelsPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * The content overview page.
     *
     * @var SearchPage
     */
    protected $searchPage;

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
        $this->dashboardPage = new DashboardPage($this);
        $this->drupalAtomApi = new DrupalAtomApi($this);
        $this->frontendLandingPage = new FrontEndLandingPage($this);
        $this->landingPagePanelsPage = new PanelsContentPage($this);
        $this->searchPage = new SearchPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Provide some test images.
        $this->images = array(
            dirname(__FILE__) . '/../../assets/sample_image.jpg',
            dirname(__FILE__) . '/../../assets/budapest.jpg',
        );

        // Go to the login page and log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }


    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Log out
        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->logout();

        parent::tearDown();
    }

    /**
     * Checks that permanent images are not deleted when removed from the pane.
     *
     * @see https://one-agency.atlassian.net/browse/KANWEBS-1731
     *
     * @group modals
     * @group panes
     * @group scald
     */
    public function testPermanentImage()
    {
        // Navigate to the  "Add content" page.
        $this->dashboardPage->go();
        $this->dashboardPage->adminMenuLinks->linkContent->click();
        $this->searchPage->checkArrival();
        $this->searchPage->adminContentLinks->linkAddContent->click();
        $this->addContentPage->checkArrival();

        // Create a landing page.
        $layout = new Paddle2Col3to9Layout();
        $nid = $this->addContentPage->createLandingPage($layout->id());
        $this->administrativeNodeViewPage->checkArrival();

        // Add a Image pane to a random region.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageLayout->click();
        $this->landingPagePanelsPage->checkArrival();

        $region = $this->landingPagePanelsPage->display->getRandomRegion();
        $region_id = $region->id();
        $image_type = new ImagePanelsContentType($this);
        $image_type->image = $this->images[0];

        $pane = $region->addPane($image_type);
        $pane_uuid = $pane->getUuid();
        $pane_xpath = $pane->getXPathSelector();
        $image_pane = new ImagePane($this, $pane_uuid, $pane_xpath);

        // Check that the image is there and save.
        $this->assertTrue($image_pane->checkImageDisplayedInPane('sample_image'));
        $this->landingPagePanelsPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The page has been updated.');

        // Check if we're being redirected correctly and publish the page.
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();

        // Now create a revision by changing the image.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageLayout->click();
        $this->landingPagePanelsPage->checkArrival();
        $image_type->image = $this->images[1];
        $pane->edit($image_type);
        $pane->editPaneModal->waitUntilClosed();

        // Check that the image is there and save.
        $image_pane = new ImagePane($this, $pane_uuid, $pane_xpath);
        $this->assertFalse($image_pane->checkImageDisplayedInPane('sample_image'));
        $this->assertTrue($image_pane->checkImageDisplayedInPane('budapest'));
        $this->landingPagePanelsPage->contextualToolbar->buttonSave->click();

        // Check that the image added to the online version is still there.
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonOnlineVersion->click();
        $this->frontendLandingPage->checkArrival();

        // Get the front-end pane.
        $frontend_region = $this->frontendLandingPage->display->region($region_id);
        $this->waitUntilElementIsDisplayed($frontend_region->getXPathSelector());

        $frontend_panes = $frontend_region->getPanes();
        $frontend_pane = !empty($frontend_panes[$pane_uuid]) ? $frontend_panes[$pane_uuid] : null;
        $this->assertNotNull($frontend_pane);
        $frontend_pane_xpath = $frontend_pane->getXPathSelector();
        $frontend_pane = new ImagePane($this, $pane_uuid, $frontend_pane_xpath);

        $this->assertTrue($frontend_pane->checkImageDisplayedInPane('sample_image'));
        $this->assertFalse($frontend_pane->checkImageDisplayedInPane('budapest'));

        // Return to the admin view.
        $previewToolbar = new PreviewToolbar($this);
        $previewToolbar->closeButton()->click();
        // Check that the image added to the revision is still there.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontendLandingPage->checkArrival();
        $front_end_pane = new ImagePane($this, $pane_uuid, $frontend_pane_xpath);
        $this->assertFalse($front_end_pane->checkImageDisplayedInPane('sample_image'));
        $this->assertTrue($front_end_pane->checkImageDisplayedInPane('budapest'));
    }

    /**
     * Tests that the user is informed if the media library is empty.
     *
     * @group scald
     * @group placeholderText
     */
    public function testLibraryEmptyText()
    {
        // Delete all existing atoms so we can test the empty text.
        // @todo Obliterating all atoms may be overkill to simply test the empty
        //   text, especially when testing with production databases where it is
        //   valuable to have lots of real life atoms available. If this becomes
        //   a problem this can be reworked by creating a custom atom type and
        //   checking the empty text on that.
        $this->drupalAtomApi->deleteAllAtoms();

        // Create a new landing page and add the image modal to it.
        $library_modal = $this->addImageModalToNewLandingPage();

        // Check that the empty text is available.
        $this->assertTextPresent('No Image has been added to the media library yet.');

        // Cancel the modals and move back to the administrative node view.
        $library_modal->close();
        $add_pane_modal = new AddPaneModal($this);
        $add_pane_modal->close();
        $this->landingPagePanelsPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
    }

    /**
     * Tests the pager in the Scald library, inside the image pane modal.
     *
     * @group modals
     * @group panes
     * @group scald
     */
    public function testLibraryPager()
    {
        // Make sure we have at least 25 items in the library so the pager
        // appears.
        while ($this->drupalAtomApi->getAtomCount('image') < 25) {
            $this->drupalAtomApi->createAtom();
        }

        // Create a new landing page and add the image modal to it.
        $library_modal = $this->addImageModalToNewLandingPage();

        // Check that there are 24 items on the first page of the modal.
        $this->assertEquals(24, $library_modal->library->getItemCount());

        // Click on the "Show more" link until we reach the end of the library.
        $pages = ceil($this->drupalAtomApi->getAtomCount('image') / 24);
        while (--$pages) {
            $library_modal->library->showMore();
        }

        // Check that the "Show more" link is no longer present when we reached
        // the last page.
        $testcase = $this;
        $callable = new SerializableClosure(
            function () use ($testcase) {
                $elements = $testcase->elements($testcase->using('css selector')->value('.paddle-scald-library-form .show-more a.button'));

                if (empty($elements)) {
                    return true;
                }
            }
        );
        $this->waitUntil($callable, $this->timeout);

        // Cancel the modals and move back to the administrative node view.
        $library_modal->close();
        $add_pane_modal = new AddPaneModal($this);
        $add_pane_modal->close();
        $this->landingPagePanelsPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
    }

    /**
     * Tests the caption in the image pane.
     *
     * @group modals
     * @group panes
     * @group scald
     */
    public function testImageCaption()
    {
        // Create a landing page.
        $this->contentCreationService->createLandingPage();
        $this->administrativeNodeViewPage->checkArrival();

        // Go to the layout page.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageLayout->click();
        $this->landingPagePanelsPage->checkArrival();

        // Add an image pane to a region.
        $region = $this->landingPagePanelsPage->display->getRandomRegion();
        $panes_before = $region->getPanes();
        $region->buttonAddPane->click();
        $add_pane_modal = new AddPaneModal($this);
        $add_pane_modal->waitUntilOpened();
        $image_pane_type = new ImagePanelsContentType($this);
        $add_pane_modal->selectContentType($image_pane_type);
        $image_pane_type->waitUntilReady();
        $image_pane_type->image = dirname(__FILE__) . '/../../assets/sample_image.jpg';
        $image_pane_type->fillInConfigurationForm();

        // Check the caption checkbox and fill out the caption textarea.
        $form = $image_pane_type->getForm();
        $form->showCaption->check();

        // Check that there is a char limit.
        $caption_text = $this->alphanumericTestDataProvider->getValidValue(600);
        $form->captionTextArea->fill($caption_text);
        $add_pane_modal->submit();
        $add_pane_modal->waitUntilClosed();
        $this->landingPagePanelsPage->checkArrival();
        $this->assertTextPresent(substr($caption_text, 0, 210));

        $this->landingPagePanelsPage->checkArrival();
        $region->refreshPaneList();
        $panes_after = $region->getPanes();

        $pane = current(array_diff_key($panes_after, $panes_before));
        $pane_uuid = $pane->getUuid();
        $pane_xpath = $pane->getXPathSelector();
        $image_pane = new ImagePane($this, $pane_uuid, $pane_xpath);

        $this->landingPagePanelsPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->assertTrue($image_pane->checkCaption(substr($caption_text, 0, 210)));
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontendLandingPage->checkArrival();
        $this->assertTrue($image_pane->checkCaption(substr($caption_text, 0, 210)));
    }

    /**
     * Tests that the autocomplete for the internal node link on the image works.
     *
     * @group modals
     * @group panes
     * @group scald
     * @group regression
     *
     * @see https://one-agency.atlassian.net/browse/KANWEBS-4239
     */
    public function testInternalLinkOnImage()
    {
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $nid = $this->contentCreationService->createBasicPage($title);

        // Go on the layout page of a landing page to add an image pane.
        $library_modal = $this->addImageModalToNewLandingPage();
        $library_modal->close();
        $image_pane_type = new ImagePanelsContentType($this);
        $image_pane_type->waitUntilReady();
        $image_pane_type->getForm()->internal->select();
        $image_pane_type->getForm()->internalUrl->fill("node/$nid");

        // Make sure the basic page is in the suggestions.
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $suggestions = $autocomplete->getSuggestions();
        $this->assertContains("$title (node/$nid)", $suggestions);

        // Close the modal and save the page so the test can logout.
        $add_pane_modal = new AddPaneModal($this);
        $add_pane_modal->close();
        $this->landingPagePanelsPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
    }

    /**
     * Helper function. Adds the image pane to a new landing page.
     *
     * @return LibraryModal
     *   The modal containing the image pane that was added.
     */
    protected function addImageModalToNewLandingPage()
    {
        // Create a landing page and go to the layout page.
        $nid = $this->contentCreationService->createLandingPage();
        $this->landingPagePanelsPage->go($nid);

        // Add the image pane on a random region.
        $region = $this->landingPagePanelsPage->display->getRandomRegion();
        $region->buttonAddPane->click();
        $add_pane_modal = new AddPaneModal($this);
        $add_pane_modal->waitUntilOpened();
        $image_pane_type = new ImagePanelsContentType($this);
        $add_pane_modal->selectContentType($image_pane_type);
        $image_pane_type->waitUntilReady();

        // Open the media library by clicking on the select button.
        $form = $image_pane_type->getForm();
        $form->image->selectButton->click();
        $library_modal = new LibraryModal($this);
        $library_modal->waitUntilOpened();

        return $library_modal;
    }
}
