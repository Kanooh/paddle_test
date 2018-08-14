<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Scald\ScaldLightboxTest
 */

namespace Kanooh\Paddle\Core\Scald;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Element\Lightbox\LightboxImage;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\Pane\ImagePane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ImagePanelsContentType;
use Kanooh\Paddle\Pages\Element\Scald\LibraryModal;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndView;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests how the Lightbox functionality is integrated with Scald images.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ScaldLightboxTest extends WebDriverTestCase
{

    /**
     * The administrative node view page.
     *
     * @var ViewPage
     */
    protected $adminNodeView;

    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The node edit page.
     *
     * @var EditPage
     */
    protected $editPage;

    /**
     * The front-end view of a node.
     *
     * @var FrontEndView
     */
    protected $frontendNodeViewPage;

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
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->adminNodeView = new ViewPage($this);
        $this->assetCreationService = new AssetCreationService($this);
        $this->editPage = new EditPage($this);
        $this->frontendNodeViewPage = new FrontEndView($this);
        $this->layoutPage = new LayoutPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Create a image atom to use for the tests.
        $this->atom = $this->assetCreationService->createImage();
    }

    /**
     * Tests the integration of Lightbox with Scald image in WYSIWYG.
     *
     * @group scald
     */
    public function testLightboxInWYSIWYG()
    {
        // Create a basic page.
        $nid = $this->contentCreationService->createBasicPage();

        // Go to its properties page.
        $this->editPage->go($nid);
        $this->editPage->body->waitUntilReady();

        // Maximize the editor to avoid problems clicking elements.
        $this->editPage->body->maximizeWindow();

        // Open the scald library modal.
        $this->editPage->body->buttonOpenScaldLibraryModal->click();
        $library_modal = new LibraryModal($this);
        $library_modal->waitUntilOpened();

        $atom_id = $this->atom['id'];
        $atom = $library_modal->library->getAtomById($atom_id);

        // Insert the atom in the CKEditor.
        $atom->insertLink->click();
        $library_modal->waitUntilClosed();

        // Double-click the image in the CKEditor.
        $test_case = $this;
        $callable = new SerializableClosure(
            function () use ($test_case, $atom_id) {
                $xpath = '//img[contains(@class, "atom-id-' . $atom_id . '")]';
                $test_case->waitUntilElementIsPresent($xpath);
                $img = $test_case->byXPath($xpath);
                $test_case->moveto($img);
                $test_case->doubleclick();
            }
        );
        $this->editPage->body->inIframe($callable);

        // Wait for the image properties modal to open.
        $image_modal = $this->editPage->body->modalImageProperties;
        $image_modal->waitUntilOpened();

        // Check that by default Lightbox is not enabled for WYSIWYG images.
        $this->assertFalse($image_modal->imageInfoForm->useLightbox->isChecked());

        // Enable lightbox for the image.
        $image_modal->imageInfoForm->useLightbox->check();
        // Select an image style.
        $image_style = 'square';
        $image_modal->imageInfoForm->imageStyle->selectOptionByValue($image_style);
        $image_modal->submit();
        $image_modal->waitUntilClosed();

        // Normalize the editor now.
        $this->editPage->body->normalizeWindow();

        // Save the page, check that the Lightbox has been added to the image.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminNodeView->checkArrival();
        $this->frontendNodeViewPage->go($nid);
        $image = new LightboxImage($this, $atom_id);
        $modal = $image->openLightbox();

        // Assert that the correct image is loaded in the lightbox.
        $this->assertTrue($modal->mainImage->displayed());
        $main_image_src = $modal->mainImage->attribute('src');
        $this->assertEquals(0, strpos(basename($main_image_src, '.jpg'), basename($this->atom['path'], '.jpg')));
        // Assert that the original image got loaded instead of the image-
        // styled one.
        $this->assertContains('/styles/' . $image_style, $image->getWebdriverElement()->attribute('src'));
        $this->assertNotContains('/styles/', $main_image_src);
        $modal->close();
        $modal->waitUntilClosed();

        // Go to node's properties page again to remove the Lightbox.
        $this->editPage->go($nid);
        $this->editPage->body->waitUntilReady();

        // Maximize the editor to avoid problems clicking elements.
        $this->editPage->body->maximizeWindow();

        // Double-click the image in the CKEditor.
        $test_case = $this;
        $callable = new SerializableClosure(
            function () use ($test_case, $atom_id) {
                $xpath = '//img[contains(@class, "atom-id-' . $atom_id . '")]';
                $test_case->waitUntilElementIsPresent($xpath);
                $img = $test_case->byXPath($xpath);
                $test_case->moveto($img);
                $test_case->doubleclick();
            }
        );
        $this->editPage->body->inIframe($callable);

        // Wait for the image properties modal to open.
        $image_modal = $this->editPage->body->modalImageProperties;
        $image_modal->waitUntilOpened();

        // Check that Lightbox is enabled.
        $this->assertTrue($image_modal->imageInfoForm->useLightbox->isChecked());

        // Now disable lightbox for the image and save.
        $image_modal->imageInfoForm->useLightbox->uncheck();
        $image_modal->submit();
        $image_modal->waitUntilClosed();

        // Normalize the editor now.
        $this->editPage->body->normalizeWindow();

        // Save the page, check that the Lightbox has been removed for the image.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminNodeView->checkArrival();
        $this->frontendNodeViewPage->go($nid);
        $this->assertImageNotUsingLightbox($atom_id);
    }

    /**
     * Tests the integration of Lightbox with Scald image panes.
     *
     * @group scald
     */
    public function testLightboxInImagePane()
    {
        // Create a basic page and go to its layout page.
        $nid = $this->contentCreationService->createBasicPage();
        $this->layoutPage->go($nid);

        // Create new image pane.
        $region = $this->layoutPage->display->getRandomRegion();
        $region_id = $region->id();
        $panes_before = $region->getPanes();

        $region->buttonAddPane->click();
        $image_content_type = new ImagePanelsContentType($this);
        $add_pane_modal = new AddPaneModal($this);
        $add_pane_modal->waitUntilOpened();
        $add_pane_modal->selectContentType($image_content_type);
        $form = $image_content_type->getForm();
        $form->image->selectButton->click();

        $library_modal = new LibraryModal($this);
        $library_modal->waitUntilOpened();
        $atom_id = $this->atom['id'];
        $atom = $library_modal->library->getAtomById($atom_id);

        // Select an atom for the pane.
        $atom->insertLink->click();
        $library_modal->waitUntilClosed();

        // Make sure "Use Lightbox" is not selected by default and select it.
        $this->assertFalse($form->lightbox->isSelected());
        $form->lightbox->select();

        // Select an image style.
        $image_style = 'square';
        $form->imageStyle->selectOptionByValue($image_style);

        // Save the pane and the page and go to the front-end to check the image.
        $add_pane_modal->submit();
        $add_pane_modal->waitUntilClosed();

        $region->refreshPaneList();
        $panes_after = $region->getPanes();

        $pane = current(array_diff_key($panes_after, $panes_before));

        $pane_uuid = $pane->getUuid();
        $pane_xpath = $pane->getXPathSelector();

        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminNodeView->checkArrival();
        $this->frontendNodeViewPage->go($nid);

        $image = new LightboxImage($this, $atom_id);
        $modal = $image->openLightbox();

        // Assert that the correct image is loaded in the lightbox.
        $this->assertTrue($modal->mainImage->displayed());
        $main_image_src = $modal->mainImage->attribute('src');
        $this->assertEquals(0, strpos(basename($main_image_src, '.jpg'), basename($this->atom['path'], '.jpg')));
        // Assert that the original image got loaded instead of the image-
        // styled one.
        $this->assertContains('/styles/' . $image_style, $image->getWebdriverElement()->attribute('src'));
        $this->assertNotContains('/styles/', $main_image_src);
        $modal->close();
        $modal->waitUntilClosed();

        // Go to node's layout page again to remove the Lightbox.
        $this->layoutPage->go($nid);
        $image_pane = new ImagePane($this, $pane_uuid, $pane_xpath);

        // Edit the pane again and remove the Lightbox effect.
        $webdriver = $this;
        $callable = new SerializableClosure(
            function () use ($image_pane, $webdriver) {
                $image_pane->toolbar->buttonEdit->click();
                $image_pane->editPaneModal->waitUntilOpened();

                $form = $image_pane->contentType->getForm();
                $webdriver->assertTrue($form->lightbox->isSelected());
                $form->noLink->select();

                $image_pane->editPaneModal->submit();
                $image_pane->editPaneModal->waitUntilClosed();
            }
        );
        $image_pane->executeAndWaitUntilReloaded($callable);

        // Save the page, check that the Lightbox has been removed for the image.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminNodeView->checkArrival();
        $this->frontendNodeViewPage->go($nid);
        $this->assertImageNotUsingLightbox($atom_id);
    }

    /**
     * Asserts that for the specified image atom Lighbox is not enabled.
     *
     * @param string $atom_id.
     *   The id of the image atom.
     */
    public function assertImageNotUsingLightbox($atom_id)
    {
        $image_only_xpath = '//img[contains(@class, "atom-id-' . $atom_id . '")]';
        $lightbox_xpath = '//a[contains(@class, "colorbox-link")]/img[contains(@class, "atom-id-' . $atom_id . '")]';

        // First make sure the image is present.
        $criteria = $this->using('xpath')->value($image_only_xpath);
        $this->assertTrue(count($this->elements($criteria)) == 1);

        // Then make sure it is not wrapped in a lighbox link.
        $criteria = $this->using('xpath')->value($lightbox_xpath);
        $this->assertTrue(count($this->elements($criteria)) == 0);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Delete any assets created during the tests.
        AssetCreationService::cleanUp($this);
        parent::tearDown();
    }
}
