<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Scald\ManualCropTest
 */

namespace Kanooh\Paddle\Core\Scald;

use Kanooh\Paddle\Pages\Admin\ContentManager\AssetsPage\AssetsPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminNodeView;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\Pane\ImagePane;
use Kanooh\Paddle\Pages\Element\Pane\Pane;
use Kanooh\Paddle\Pages\Element\Pane\PaneSectionTop;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ImagePanelsContentType;
use Kanooh\Paddle\Pages\Element\Scald\AddAssetModal;
use Kanooh\Paddle\Pages\Element\Scald\AddAtomModal;
use Kanooh\Paddle\Pages\Element\Scald\Image\AddOptionsModal;
use Kanooh\Paddle\Pages\Element\Scald\LibraryModal;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as NodeViewPage;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\ScaldService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests how the Manual Crop functionality is integrated with Scald images.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ManualCropTest extends WebDriverTestCase
{

    /**
     * The administrative node view page.
     *
     * @var AdminNodeView
     */
    protected $adminNodeView;

    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * The assets library page.
     *
     * @var AssetsPage
     */
    protected $assetsPage;

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
     * The panels display of a basic page.
     *
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * The frontend node view page.
     *
     * @var NodeViewPage
     */
    protected $nodeViewPage;

    /**
     * @var ScaldService
     */
    protected $scaldService;

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

        $this->adminNodeView = new AdminNodeView($this);
        $this->assetCreationService = new AssetCreationService($this);
        $this->assetsPage = new AssetsPage($this);
        $this->editPage = new EditPage($this);
        $this->layoutPage = new LayoutPage($this);
        $this->nodeViewPage = new NodeViewPage($this);
        $this->scaldService = new ScaldService($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Tests that the Manual Crop setting are correctly save for images.
     *
     * @group scald
     * @group manualCrop
     */
    public function testManualCropSave()
    {
        $this->assetsPage->go();
        $this->assetsPage->contextualToolbar->buttonAddNewAsset->click();
        $add_asset_modal = new AddAssetModal($this);
        $add_asset_modal->waitUntilOpened();
        $add_asset_modal->imageLink->click();

        // Upload a new image.
        $add_image_modal = new AddAtomModal($this);
        $add_image_modal->waitUntilOpened();

        $image_path = dirname(__FILE__) . '/../../assets/sample_image.jpg';
        $image = $this->file($image_path);
        $add_image_modal->form->fileList->uploadFiles($image);

        // Select a manual crop ration.
        $options_modal = new AddOptionsModal($this);
        $options_modal->waitUntilOpened();
        $options_modal->form->alternativeText->fill('alt');

        // Select one of the options.
        $options_modal->form->setCroppedStyle('square');

        // Make sure that only the desired styled was cropped.
        $cropped_styles = $options_modal->form->getCroppedStyles();
        $this->assertCount(1, $cropped_styles);
        $this->assertEquals('square', $cropped_styles[0]);

        // Now select another style.
        $options_modal->form->setCroppedStyle('16_9');

        // Make sure that both style are now cropped.
        $cropped_styles = $options_modal->form->getCroppedStyles();
        $this->assertCount(2, $cropped_styles);
        $this->assertTrue(in_array('16_9', $cropped_styles));

        // Now select another style.
        $options_modal->form->setCroppedStyle('3_1');

        // Make sure that all styles are now cropped.
        $cropped_styles = $options_modal->form->getCroppedStyles();
        $this->assertCount(3, $cropped_styles);
        $this->assertTrue(in_array('3_1', $cropped_styles));

        $options_modal->form->finishButton->click();
    }

    /**
     * Tests the Manual Cropping of the image in the pane top section.
     *
     * @group scald
     * @group manualCrop
     */
    public function testManualCroppingTopSectionImage()
    {
        // Create an image atom with a crop style.
        $data = array(
            'path' => dirname(__FILE__) . '/../../assets/sample_image.jpg',
            'image_style' => '16_9',
        );
        $data = $this->assetCreationService->createImage($data);

        // Create a page and add a pane to it.
        $nid = $this->contentCreationService->createBasicPage();
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();

        $panes_before = $region->getPanes();
        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');

        $modal = new AddPaneModal($this);
        $modal->waitUntilOpened();
        $custom_content_pane = new CustomContentPanelsContentType($this);
        $modal->selectContentType($custom_content_pane);

        $custom_content_pane->topSection->enable->check();
        $custom_content_pane->topSection->contentTypeRadios->image->select();
        $custom_content_pane->topSection->image->selectButton->click();

        $this->scaldService->insertAtom($data['id']);

        $custom_content_pane->topSection->imageStyle->selectOptionByValue($data['image_style']);
        $modal->submit();
        $modal->waitUntilClosed();

        $region->refreshPaneList();
        $panes_after = $region->getPanes();
        /** @var Pane $pane_new */
        $pane_new = current(array_diff_key($panes_after, $panes_before));

        // Assert that the image source is what we expect.
        $xpath = '//div[@data-pane-uuid = "' . $pane_new->getUuid() . '"]//div[contains(@class, "pane-section-top")]';
        $top_section = new PaneSectionTop($this, $xpath);
        $image = $top_section->getSectionImage();

        $this->assertCorrectImageStyleGotRequested($data, $image);
    }

    /**
     * Tests the Manual Cropping of the image in the image pane.
     *
     * Also extensively covers updating the thumbnail and the style selection
     * when the other gets changed.
     *
     * @group scald
     * @group manualCrop
     */
    public function testManualCroppingImagePane()
    {
        // Create an image atom with a crop style.
        $data = array(
            'path' => dirname(__FILE__) . '/../../assets/budapest.jpg',
            'image_style' => 'square',
        );
        $data = $this->assetCreationService->createImage($data);

        // Create a page and add a pane to it.
        $nid = $this->contentCreationService->createBasicPage();
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();

        $panes_before = $region->getPanes();
        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');

        $image_pane = new ImagePanelsContentType($this);
        $modal = new AddPaneModal($this);
        $modal->waitUntilOpened();
        $modal->selectContentType($image_pane);

        // Select an image style before selecting an image.
        $image_pane->getForm()->imageStyle->selectOptionByValue($data['image_style']);

        // Select an image.
        $image_pane->getForm()->image->selectButton->click();
        $this->scaldService->insertAtom($data['id']);

        // Ensure the image style got reset because a new image was chosen.
        $image_pane->getForm()->imageStyle->waitUntilSelectedValueEquals('');

        // Ensure the selected image is shown.
        $selected_images = $image_pane->getForm()->image->atoms;
        $this->assertCount(1, $selected_images);

        $image_pane->getForm()->imageStyle->selectOptionByValue($data['image_style']);
        // Ensure the image preview got updated with the correct style.
        $this->assertCorrectImageStyleGotRequested($data, $selected_images[0]->previewThumbnail);

        $modal->submit();
        $modal->waitUntilClosed();

        $region->refreshPaneList();
        $panes_after = $region->getPanes();
        /** @var Pane $pane_new */
        $pane_new = current(array_diff_key($panes_after, $panes_before));

        // Assert that the image source is what we expect.
        $xpath = '//div[@data-pane-uuid = "' . $pane_new->getUuid() . '"]';
        $rendered_image_pane = new ImagePane($this, $pane_new->getUuid(), $xpath);

        $this->assertCorrectImageStyleGotRequested($data, $rendered_image_pane->mainImage);

        // Edit the pane.
        $pane_new->toolbar->buttonEdit->click();
        $image_pane->waitUntilReady();

        // Ensure the image style is still applied.
        $selected_images = $image_pane->getForm()->image->atoms;
        $this->assertCount(1, $selected_images);
        $this->assertCorrectImageStyleGotRequested($data, $selected_images[0]->previewThumbnail);

        // Set a top section image.
        $top_section_image_data = $data;
        $top_section_image_data['image_style'] = '16_9';
        $image_pane->topSection->enable->check();
        $image_pane->topSection->contentTypeRadios->image->select();
        $image_pane->topSection->image->selectButton->click();
        $this->scaldService->insertAtom($data['id']);

        // Ensure the selected image is shown.
        $selected_top_section_images = $image_pane->topSection->image->atoms;
        $this->assertCount(1, $selected_top_section_images);

        // Select a top section image style.
        $image_pane->topSection->imageStyle->selectOptionByValue($top_section_image_data['image_style']);
        // Ensure the image preview got updated with the correct style.
        $this->assertCorrectImageStyleGotRequested($top_section_image_data, $selected_top_section_images[0]->previewThumbnail);

        // Remove pane image.
        $selected_images[0]->removeButton->click();

        // Ensure the pane image style gets reset.
        $image_pane->getForm()->imageStyle->waitUntilSelectedValueEquals('');

        // Ensure the top section image style stayed untouched.
        $this->assertEquals(
            $top_section_image_data['image_style'],
            $image_pane->topSection->imageStyle->getSelectedValue()
        );

        $modal->submit();
        $modal->waitUntilClosed();
    }

    /**
     * Ensure the image path is correct according to the selected image style.
     *
     * @param array $image_data
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $image_element
     */
    public function assertCorrectImageStyleGotRequested($image_data, $image_element)
    {
        // Generate the path to the styled image.
        $scald_atom = scald_atom_load($image_data['id']);
        $expected_src = file_create_url(image_style_path($image_data['image_style'], $scald_atom->file_source));

        // The images may the 'itok' query parameter appended to the url.
        // Assert that the string starts with the expected path.
        $this->assertStringStartsWith($expected_src, $image_element->attribute('src'));
    }

    /**
     * Tests the fallback cropping when there is no manual crop selected.
     *
     * @group scald
     * @group manualCrop
     */
    public function testFallbackCrop()
    {
        // Create an image atom with a crop style.
        $data = array(
          'path' => dirname(__FILE__) . '/../../assets/budapest.jpg',
        );
        $data = $this->assetCreationService->createImage($data);

        // Create a page and add a pane to it.
        $nid = $this->contentCreationService->createBasicPage();
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();

        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');

        $image_pane = new ImagePanelsContentType($this);
        $modal = new AddPaneModal($this);
        $modal->waitUntilOpened();
        $modal->selectContentType($image_pane);

        // Add the top section image.
        $image_pane->topSection->enable->check();
        $image_pane->topSection->contentTypeRadios->image->select();
        $image_pane->topSection->image->selectButton->click();

        $this->scaldService->insertAtom($data['id']);
        $image_pane->topSection->imageStyle->selectOptionByValue('2_3');

        // Add the main image.
        $image_pane->getForm()->image->selectButton->click();
        $this->scaldService->insertAtom($data['id']);
        $image_pane->getForm()->imageStyle->selectOptionByValue('3_2');
        $modal->submit();
        $modal->waitUntilClosed();

        // Save the page and go to the frontend.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminNodeView->checkArrival();

        // At this point, the image style have been generated.
        // We can't rely on the frontend display as the width and height
        // that will be retrieved by selenium are tempered by css styles.
        // We need to load the images generated and verify the sizes from there.
        $atom = scald_atom_load($data['id']);

        $image_style_uri = image_style_path('2_3', $atom->file_source);
        $resource = image_load($image_style_uri);
        $this->assertEquals(370, $resource->info['width']);
        $this->assertEquals(555, $resource->info['height']);

        $image_style_uri = image_style_path('3_2', $atom->file_source);
        $resource = image_load($image_style_uri);
        $this->assertEquals(660, $resource->info['width']);
        $this->assertEquals(440, $resource->info['height']);
    }

    /**
     * Tests the CKEditor integration.
     *
     * @group scald
     * @group wysiwyg
     */
    public function testCKEditorIntegration()
    {
        // Create an image atom with a crop style.
        $data = array(
          'path' => dirname(__FILE__) . '/../../assets/sample_image.jpg',
          'image_style' => '16_9',
        );
        $data = $this->assetCreationService->createImage($data);

        // Create a basic page and edit it.
        $nid = $this->contentCreationService->createBasicPage();
        $this->editPage->go($nid);

        // Maximize the editor to avoid problems clicking elements.
        $this->editPage->body->waitUntilReady();
        $this->editPage->body->maximizeWindow();

        // Insert the atom in the editor.
        $this->editPage->body->buttonOpenScaldLibraryModal->click();
        $library_modal = new LibraryModal($this);
        $library_modal->waitUntilOpened();
        $atom = $library_modal->library->getAtomById($data['id']);
        $atom->insertLink->click();
        $library_modal->waitUntilClosed();

        // Open the image dialog.
        $this->editPage->body->openImagePropertiesModal($data['id']);
        $image_modal = $this->editPage->body->modalImageProperties;

        // Verify that no style is selected by default.
        $this->assertEquals('', $image_modal->imageInfoForm->imageStyle->getSelectedValue());
        $this->assertEquals('No style', $image_modal->imageInfoForm->imageStyle->getSelectedLabel());

        // Verify that the select contains all expected styles.
        $expected_styles = manualcrop_styles_with_crop(false, null, true);
        $this->assertEquals($expected_styles, $image_modal->imageInfoForm->imageStyle->getOptions());

        // Select a new style.
        $image_modal->imageInfoForm->selectImageStyle($data['image_style']);

        // Generate the path to the styled image.
        $scald_atom = scald_atom_load($data['id']);
        $expected_src = file_create_url(image_style_path($data['image_style'], $scald_atom->file_source));

        // The images may the 'itok' query parameter appended to the url.
        // Assert that the string starts with the expected path.
        $this->assertStringStartsWith($expected_src, $image_modal->imageInfoForm->url->getContent());

        // Close the modal.
        $image_modal->submit();
        $image_modal->waitUntilClosed();

        // Save the page and go to the frontend view.
        $this->editPage->body->normalizeWindow();
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminNodeView->checkArrival();
        $this->nodeViewPage->go($nid);

        // Find our image.
        $element = $this->byClassName('atom-id-' . $data['id']);

        // Verify the image url.
        $this->assertStringStartsWith($expected_src, $element->attribute('src'));

        // Edit the page to verify that values are kept.
        $this->editPage->go($nid);

        // Maximize the editor to avoid problems clicking elements.
        $this->editPage->body->waitUntilReady();
        $this->editPage->body->maximizeWindow();

        // Open the image properties again.
        $this->editPage->body->openImagePropertiesModal($data['id']);
        $image_modal = $this->editPage->body->modalImageProperties;

        // Verify that the style selection has been kept.
        $this->assertEquals($data['image_style'], $image_modal->imageInfoForm->imageStyle->getSelectedValue());

        // Change the value back to the original image.
        $image_modal->imageInfoForm->selectImageStyle('');

        // Generate the path to the normal image.
        $expected_src = file_create_url($scald_atom->file_source);

        // The images may the 'itok' query parameter appended to the url.
        // Assert that the string starts with the expected path.
        $this->assertStringStartsWith($expected_src, $image_modal->imageInfoForm->url->getContent());

        // Close the modal.
        $image_modal->submit();
        $image_modal->waitUntilClosed();

        // Save the page and go to the frontend view.
        $this->editPage->body->normalizeWindow();
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminNodeView->checkArrival();
        $this->nodeViewPage->go($nid);

        // Find our image again.
        $element = $this->byClassName('atom-id-' . $data['id']);

        // Verify the image url.
        $this->assertStringStartsWith($expected_src, $element->attribute('src'));

        // Verify that there is no file scheme property.
        $this->assertNull($element->attribute('data-file-scheme'));
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
