<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Carousel\PaneTest.
 */

namespace Kanooh\Paddle\App\Carousel;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\Carousel;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\Pane\Carousel\Carousel as CarouselPane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CarouselPanelsContentType;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndViewPage;
use Kanooh\Paddle\Utilities\DrupalApi\DrupalAjaxApi;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the carousel pane.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneTest extends WebDriverTestCase
{
    /**
     * @var AppService
     */
    protected $appService;

    /**
     * The administrative node view of a page.
     *
     * @var ViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The front-end view of a landing page.
     *
     * @var FrontEndViewPage
     */
    protected $frontendPage;

    /**
     * Landing page layout page.
     *
     * @var PanelsContentPage
     */
    protected $layoutPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * Test data provider for alphanumeric data.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Instantiate some service classes for later use.
        $this->administrativeNodeViewPage = new ViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->frontendPage = new FrontEndViewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->assetCreationService = new AssetCreationService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Instantiate some common page classes for later use.
        $this->layoutPage = new PanelsContentPage($this);

        // Enable the carousel app if it's not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Carousel);

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Tear down method.
     */
    public function tearDown()
    {
        // Delete any assets created during the tests.
        AssetCreationService::cleanUp($this);
        parent::tearDown();
    }

    /**
     * Tests the basic configuration and functionality of the pane.
     *
     * @group carousel
     * @group panes
     * @group scald
     */
    public function testPane()
    {
        // Create a basic page to link to in one of the slides.
        $node_title = $this->alphanumericTestDataProvider->getValidValue();
        $test_nid = $this->contentCreationService->createBasicPage($node_title);

        // Define the test data for our slides.
        $long_url = 'http://' . $this->alphanumericTestDataProvider->getValidValue(244) . '.com';
        $test_data = array(
            array(
                'image' => array(
                    'path' => 'sample_image.jpg',
                ),
                'url' => array(
                    'type' => 'no_link',
                    'valid_link' => null,
                    'invalid_link' => null,
                ),
                'caption' => $this->alphanumericTestDataProvider->getValidValue(),
            ),
            array(
                'image' => array(
                    'path' => 'budapest.jpg',
                ),
                'url' => array(
                    'type' => 'internal',
                    'valid_link' => 'node/' . $test_nid,
                    'invalid_link' => 'node/111',
                ),
                'caption' => false,
            ),
            array(
                'image' => array(
                    'path' => 'sample_video.jpg',
                ),
                'url' => array(
                    'type' => 'external',
                    'valid_link' => $long_url,
                    'invalid_link' => 'http://<script>alert("Boo hoo");</script>',
                ),
                'caption' => false,
            ),
        );

        // Create some image assets with the test data.
        foreach ($test_data as $index => $data) {
            $image = $this->assetCreationService->createImage(
                array(
                    'path' => $this->assetCreationService->assetsPath . '/' . $data['image']['path'],
                )
            );
            $test_data[$index]['image'] += $image;
        }

        // Create a landing page.
        $nid = $this->contentCreationService->createLandingPage();
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();

        // Create a carousel pane.
        $content_type = new CarouselPanelsContentType($this);
        $webdriver = $this;
        $callable = new SerializableClosure(
            function ($modal) use ($content_type, $test_data, $webdriver) {
                // Submit the modal without creating any slides, and verify that we get
                // a validation error.
                $modal->submit();
                $webdriver->waitUntilTextIsPresent('A carousel should consist of at least one slide.');

                // Add a new slide.
                $content_type->addSlide();

                // Submit the modal, and check that we get a new validation error
                // because we didn't select an image.
                $modal->submit();
                $webdriver->waitUntilTextIsPresent('Image field is required.');

                // Add two other slides.
                $content_type->addSlide();
                $content_type->addSlide();

                // Get the configuration form.
                $form = $content_type->getForm();

                // Add the images we created earlier to the slides.
                /* @var \Kanooh\Paddle\Pages\Element\PanelsContentType\Carousel\SlideForm[] $slides */
                $slides = array_values($form->slides);

                for ($i = 0; $i < count($slides); $i++) {
                    // Set the image file.
                    $slides[$i]->selectImage($test_data[$i]['image']['id']);

                    // Set the link value for the slide.
                    if ($test_data[$i]['url']['type'] == 'internal') {
                        $slides[$i]->urlTypeInternalLink->select();

                        // Check the URL validation.
                        $slides[$i]->internalUrl->fill($test_data[$i]['url']['invalid_link']);
                        $modal->submit();
                        $webdriver->waitUntilTextIsPresent('Please enter a valid URL for the internal URL.');

                        // Enter a valid value now.
                        $form = $content_type->getForm();
                        $slides = array_values($form->slides);
                        $slides[$i]->internalUrl->fill($test_data[$i]['url']['valid_link']);
                    } elseif ($test_data[$i]['url']['type'] == 'external') {
                        $slides[$i]->urlTypeExternalLink->select();

                        // Check the URL validation.
                        $slides[$i]->externalUrl->fill($test_data[$i]['url']['invalid_link']);
                        $modal->submit();
                        $webdriver->waitUntilTextIsPresent('Please enter a valid URL for the external URL.');

                        // Enter a valid value now.
                        $form = $content_type->getForm();
                        $slides = array_values($form->slides);
                        $slides[$i]->externalUrl->fill($test_data[$i]['url']['valid_link']);
                    }

                    // Add a caption if we have one for this slide.
                    if ($test_data[$i]['caption'] !== false) {
                        $slides[$i]->caption->fill($test_data[$i]['caption']);
                    }
                }
            }
        );
        $pane = $region->addPane($content_type, $callable);

        $pane_uuid = $pane->getUuid();
        $pane_xpath = $pane->getXPathSelector();

        $carousel_pane = new CarouselPane($this, $pane_uuid, $pane_xpath);

        // Before asserting the slides change the internal url to the node title
        // as it will be in the Drupal alias.
        $test_data[1]['url']['valid_link'] = $node_title;

        // Make sure that the slides show in the correct order with the correct
        // links.
        $this->assertSlideData($carousel_pane, $test_data);

        // Edit the pane, re-arrange the first slide to become the second slide.
        $callable = new SerializableClosure(
            function () use ($carousel_pane) {
                $carousel_pane->toolbar->buttonEdit->click();
                $carousel_pane->editPaneModal->waitUntilOpened();
                    $form = $carousel_pane->contentType->getForm();
                    $slides = array_values($form->slides);
                    $form->dragSlideTo($slides[0], $slides[1]);

                $carousel_pane->editPaneModal->submit();
                $carousel_pane->editPaneModal->waitUntilClosed();
            }
        );
        $carousel_pane->executeAndWaitUntilReloaded($callable);

        // Swap the first and second data sets.
        $first_data = $test_data[0];
        $test_data[0] = $test_data[1];
        $test_data[1] = $first_data;

        // Make sure that the slides are in the same order as the swapped test
        // data.
        $this->assertSlideData($carousel_pane, $test_data);

        // Edit the pane again, remove the second slide.
        $callable = new SerializableClosure(
            function () use ($carousel_pane) {
                $carousel_pane->toolbar->buttonEdit->click();
                $carousel_pane->editPaneModal->waitUntilOpened();
                $content_type = $carousel_pane->contentType;
                $content_type->removeSlide(1);
                $carousel_pane->editPaneModal->submit();
                $carousel_pane->editPaneModal->waitUntilClosed();
            }
        );
        $carousel_pane->executeAndWaitUntilReloaded($callable);

        // Now remove the second data set.
        unset($test_data[1]);
        $test_data = array_values($test_data);

        // Make sure the carousel shows the same atoms as the ones in the test
        // data.
        $this->assertSlideData($carousel_pane, $test_data);

        // Now check the anonymous access to the node URL.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->waitUntilPageIsLoaded();

        // Logout to visit the front-end as anonymous user.
        $this->userSessionService->logout();
        $this->frontendPage->go($nid);

        // Get the pane on the front-end.
        $carousel_pane = new CarouselPane($this, $pane_uuid, '//div[@data-pane-uuid="' . $pane_uuid . '"]');
        // Verify that links to unpublished node are not accessible for
        // anonymous users.
        $slides = array_values($carousel_pane->slides);
        $this->assertNotNull($slides[0]);
        $this->assertNull($slides[0]->link);

        // Check that the external URL is displayed in its full length.
        $actual = strtolower(trim($slides[1]->link->attribute('href'), '/'));
        $expected = strtolower($long_url);
        $this->assertEquals($expected, $actual);

        $this->userSessionService->login('ChiefEditor');

        // First publish the test basic page.
        $this->administrativeNodeViewPage->go($test_nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        $this->layoutPage->go($nid);
        $carousel_pane = new CarouselPane($this, $pane_uuid, $pane_xpath);

        // Edit the pane again and set the second link which was external to
        // point to the front page.
        $callable = new SerializableClosure(
            function () use ($carousel_pane) {
                $carousel_pane->toolbar->buttonEdit->click();
                $carousel_pane->editPaneModal->waitUntilOpened();

                $form = $carousel_pane->contentType->getForm();
                $slides = array_values($form->slides);
                $slides[1]->urlTypeInternalLink->select();
                $slides[1]->internalUrl->fill('<front>');

                $carousel_pane->editPaneModal->submit();
                $carousel_pane->editPaneModal->waitUntilClosed();
            }
        );
        $carousel_pane->executeAndWaitUntilReloaded($callable);
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontendPage->checkArrival();
        $url = $this->url();

        // Logout to visit the front-end as anonymous user.
        $this->userSessionService->logout();
        $this->url($url);

        // Make sure both link are there for anonymous user.
        $carousel_pane = new CarouselPane($this, $pane_uuid, '//div[@data-pane-uuid="' . $pane_uuid . '"]');
        $slides = array_values($carousel_pane->slides);
        $this->assertNotNull($slides[0]->link);
        $this->assertNotNull($slides[1]->link);

        // Log in as chief editor so we can delete the created assets in the
        // tear down.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Tests the slide counter when multiple carousels are present.
     *
     * @group carousel
     * @group panes
     * @group scald
     */
    public function testSlideCounter()
    {
        // Create 5 images for the carousels.
        $images = array();
        for ($i = 0; $i < 5; $i++) {
            $images[] = $this->assetCreationService->createImage();
        }

        // Create a landing page and go to its layout page.
        $nid = $this->contentCreationService->createLandingPage();
        $this->layoutPage->go($nid);

        // Get a random region to add the carousels to.
        // Create a carousel pane.
        $content_type = new CarouselPanelsContentType($this);
        $region = $this->layoutPage->display->getRandomRegion();
        $callable = new SerializableClosure(
            function () use ($content_type, $images) {
                // Add the first 3 images to the carousel.
                for ($i = 0; $i < 3; $i++) {
                    $content_type->addSlide();
                    $form = $content_type->getForm();

                    /* @var \Kanooh\Paddle\Pages\Element\PanelsContentType\Carousel\SlideForm[] $slides */
                    $slides = array_values($form->slides);
                    $slide = $slides[$i];
                    $slide->image->selectAtom($images[$i]['id']);
                }
            }
        );
        $first_pane = $region->addPane($content_type, $callable);
        $first_pane_uuid = $first_pane->getUuid();

        // Create a second carousel pane.
        $region = $this->layoutPage->display->getRandomRegion();
        $callable = new SerializableClosure(
            function () use ($content_type, $images) {
                // Add the two other images to this carousel.
                for ($i = 3; $i < 5; $i++) {
                    $content_type->addSlide();
                    $form = $content_type->getForm();

                    /* @var \Kanooh\Paddle\Pages\Element\PanelsContentType\Carousel\SlideForm[] $slides */
                    $slides = array_values($form->slides);
                    $slide = $slides[$i-3];
                    $slide->image->selectAtom($images[$i]['id']);
                }
            }
        );
        $second_pane = $region->addPane($content_type, $callable);
        $second_pane_uuid = $second_pane->getUuid();

        // Save the page, publish it, and go to its preview.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontendPage->checkArrival();

        // Get both carousel panes from the page.
        $first_carousel_pane = new CarouselPane($this, $first_pane_uuid);
        $second_carousel_pane = new CarouselPane($this, $second_pane_uuid);

        $first_carousel_pane->waitUntilSlideCounterVisible();
        $second_carousel_pane->waitUntilSlideCounterVisible();

        // Make sure that both the first and second carousel show the correct
        // number of slides and the correct active slide number.
        $this->assertEquals(1, $first_carousel_pane->currentSlideNumber);
        $this->assertEquals(3, $first_carousel_pane->totalSlidesNumber);
        $this->assertEquals(1, $second_carousel_pane->currentSlideNumber);
        $this->assertEquals(2, $second_carousel_pane->totalSlidesNumber);

        // Navigate to the second slide on the first carousel.
        $first_carousel_pane->nextSlide();

        // Verify again that the number of slides is displayed correctly, and
        // that the active slide number has been updated on the first slide.
        $this->assertEquals(2, $first_carousel_pane->currentSlideNumber);
        $this->assertEquals(3, $first_carousel_pane->totalSlidesNumber);
        $this->assertEquals(1, $second_carousel_pane->currentSlideNumber);
        $this->assertEquals(2, $second_carousel_pane->totalSlidesNumber);
    }

    /**
     * Asserts that the slides in a carousel pane show the correct data.
     *
     * @param CarouselPane $pane
     *   The carousel pane.
     * @param array $test_data
     *   Array of test slides, in the order that they should be in the carousel.
     */
    protected function assertSlideData($pane, $test_data)
    {
        // Loop over the slides that the carousel is displaying.
        $slides = array_values($pane->slides);
        for ($i = 0; $i < count($test_data); $i++) {
            /* @var \Kanooh\Paddle\Pages\Element\Pane\Carousel\Slide $slide */
            $slide = $slides[$i];
            $data = $test_data[$i];

            // Make sure the slide displays the correct atom.
            $this->assertEquals($data['image']['id'], $slide->atomId);

            // Make sure that the link's href is correct, or that there is no
            // link.
            if ($data['url']['type'] == 'no_link') {
                $this->assertNull($slide->link);
            } else {
                $expected_link = strtolower($data['url']['valid_link']);
                $actual_link = strtolower($slide->link->attribute('href'));
                $this->assertContains($expected_link, $actual_link);
            }

            // Check for "target" attribute on external links.
            if ($data['url']['type'] == 'external') {
                $this->assertEquals('_blank', $slide->link->attribute('target'));
            }

            // Make sure that the caption is correct, or invisible.
            $this->assertEquals($data['caption'], $slide->caption);
        }
    }

    /**
     * Tests the play/pause buttons of the carousel.
     *
     * @group carousel
     * @group panes
     * @group scald
     */
    public function testPlayPauseButtons()
    {
        // Create 3 images for the carousels.
        $images = array();
        for ($i = 0; $i < 3; $i++) {
            $images[] = $this->assetCreationService->createImage();
        }

        // Create a landing page and go to its layout page.
        $nid = $this->contentCreationService->createLandingPage();
        $this->layoutPage->go($nid);

        // Get a random region to add the carousel to.
        $content_type = new CarouselPanelsContentType($this);
        $region = $this->layoutPage->display->getRandomRegion();
        $callable = new SerializableClosure(
            function () use ($content_type, $images) {
                for ($i = 0; $i < 3; $i++) {
                    $content_type->addSlide();
                    $form = $content_type->getForm();

                    /* @var \Kanooh\Paddle\Pages\Element\PanelsContentType\Carousel\SlideForm[] $slides */
                    $slides = array_values($form->slides);
                    $slide = $slides[$i];
                    $slide->image->selectAtom($images[$i]['id']);
                }
            }
        );
        $pane = $region->addPane($content_type, $callable);
        $pane_uuid = $pane->getUuid();
        $pane_xpath = $pane->getXPathSelector();

        // Save the page, publish it, and go to its preview.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontendPage->checkArrival();

        // Get the carousel pane from the page.
        $carousel_pane = new CarouselPane($this, $pane_uuid);

        $carousel_pane->waitUntilSlideCounterVisible();
        // When the autoplay is off in the pane config the play button will be
        // displayed.
        $this->assertTrue($carousel_pane->playButton->displayed());
        $this->assertEquals(1, $carousel_pane->currentSlideNumber);
        $carousel_pane->playButton->click();

        // Check the data attribute for the slider speed.
        $this->assertEquals(5, $carousel_pane->sliderSpeed/1000);

        // Check that the play button works.
        for ($i = 1; $i < 1 + $carousel_pane->totalSlidesNumber; $i ++) {
            // We just do the wait because an assert is not needed. It will
            // return a timeout anyway the slide has need been found.
            $carousel_pane->waitUntilSlideReached($i);
            $this->assertTrue($carousel_pane->pauseButton->displayed());
        }

        // Go back to the carousel config and change the setting to autoplay
        // the carousel.
        $this->layoutPage->go($nid);
        $carousel_pane = new CarouselPane($this, $pane_uuid, $pane_xpath);

        $callable = new SerializableClosure(
            function () use ($carousel_pane) {
                $carousel_pane->toolbar->buttonEdit->click();
                $carousel_pane->editPaneModal->waitUntilOpened();

                $form = $carousel_pane->contentType->getForm();
                $form->autoplay->check();

                // Set the slider speed to some non-default value.
                $form->sliderSpeedDropdown->selectOptionByValue(2);

                $carousel_pane->editPaneModal->submit();
                $carousel_pane->editPaneModal->waitUntilClosed();
            }
        );
        $carousel_pane->executeAndWaitUntilReloaded($callable);
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontendPage->checkArrival();

        // Get the carousel pane from the page.
        $carousel_pane = new CarouselPane($this, $pane_uuid);

        // Check the data attribute for the slider speed.
        $this->assertEquals(2, $carousel_pane->sliderSpeed/1000);

        // When the autoplay is on in the pane config the pause button will be
        // displayed.
        $this->assertTrue($carousel_pane->pauseButton->displayed());
        for ($i = 1; $i < 1 + $carousel_pane->totalSlidesNumber; $i++) {
            // We just do the wait because an assert is not needed. It will
            // return a timeout anyway the slide has need been found.
            $carousel_pane->waitUntilSlideReached($i);
        }
    }

    public function testImageFieldsCropStyles()
    {
        // Create 3 images for the carousels.
        $data = array(
            array('image_style' => ''),
            array('image_style' => '3_2'),
            array('image_style' => '2_3'),
            array('image_style' => '3_1'),
        );
        $images = array();
        for ($i = 0; $i < 4; $i++) {
            $images[] = $this->assetCreationService->createImage($data[$i]);
        }

        // Create a landing page and go to its layout page.
        $nid = $this->contentCreationService->createLandingPage();
        $this->layoutPage->go($nid);

        // Get a random region to add the carousel to.
        $content_type = new CarouselPanelsContentType($this);
        $region = $this->layoutPage->display->getRandomRegion();
        $webdriver = $this;
        $callable = new SerializableClosure(
            function () use ($content_type, $images, $webdriver) {
                $drupalAjaxApi = new DrupalAjaxApi($webdriver);
                for ($i = 0; $i < 4; $i++) {
                    $content_type->addSlide();
                    $form = $content_type->getForm();

                    /* @var \Kanooh\Paddle\Pages\Element\PanelsContentType\Carousel\SlideForm[] $slides */
                    $slides = array_values($form->slides);
                    $slide = $slides[$i];
                    $slide->image->selectAtom($images[$i]['id']);
                    $slide->image->style->selectOptionByValue($images[$i]['image_style']);
                    $drupalAjaxApi->waitUntilElementFinishedAjaxing($slide->image->style->getWebdriverElement());
                }
            }
        );
        /* @var \Kanooh\Paddle\Pages\Element\Pane\Carousel\Carousel $pane */
        $pane = $region->addPane($content_type, $callable);
        $pane_uuid = $pane->getUuid();
        $pane_xpath = $pane->getXPathSelector();

        // Reopen the pane to verify that the values are in place.
        $pane = new CarouselPane($this, $pane_uuid, $pane_xpath);
        $pane->toolbar->buttonEdit->click();
        $pane->editPaneModal->waitUntilOpened();
        /* @var \Kanooh\Paddle\Pages\Element\PanelsContentType\Carousel\SlideForm[] $slides */
        $slides = array_values($pane->contentType->getForm()->slides);
        for ($i = 0; $i < 4; $i++) {
            $image = $slides[$i]->image;
            $this->assertEquals($images[$i]['id'], $image->valueField->value());
            $this->assertEquals($images[$i]['image_style'], $image->style->getSelectedValue());
        }

        // Close the pane.
        $pane->editPaneModal->close();
        $pane->editPaneModal->waitUntilClosed();

        // Save the page and go to its preview.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPreviewRevision->click();
        $this->frontendPage->checkArrival();

        // Get the carousel pane from the page.
        $carousel_pane = new CarouselPane($this, $pane_uuid);
        $slides = array_values($carousel_pane->slides);

        // Verify that the images in the carousel are the one we want.
        for ($i = 0; $i < 4; $i++) {
            $scald_atom = scald_atom_load($data[$i]['id']);
            $uri = $scald_atom->file_source;
            if (!empty($data[$i]['image_style'])) {
                $uri = image_style_path($data[$i]['image_style'], $uri);
            }
            $expected_src = file_create_url($uri);
            $this->assertStringStartsWith($expected_src, $slides[$i]->image->attribute('src'));
        }
    }

    /**
     * Tests that the autocomplete for the internal node link on the slide works.
     * @group modals
     * @group panes
     * @group scald
     * @group regression
     *
     * @see https://one-agency.atlassian.net/browse/KANWEBS-4239
     */
    public function testInternalLinkOnSlide()
    {
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $nid = $this->contentCreationService->createBasicPage($title);

        // Create a landing page to add a carousel pane.
        $lp_nid = $this->contentCreationService->createLandingPage();
        $this->layoutPage->go($lp_nid);

        // Add the image pane on a random region.
        $region = $this->layoutPage->display->getRandomRegion();
        $region->buttonAddPane->click();
        $add_pane_modal = new AddPaneModal($this);
        $add_pane_modal->waitUntilOpened();
        $carousel_pane_type = new CarouselPanelsContentType($this);
        $add_pane_modal->selectContentType($carousel_pane_type);
        $carousel_pane_type->waitUntilReady();

        $carousel_pane_type->addSlide();
        /* @var \Kanooh\Paddle\Pages\Element\PanelsContentType\Carousel\SlideForm[] $slides */
        $slides = array_values($carousel_pane_type->getForm()->slides);
        $slides[0]->urlTypeInternalLink->select();
        $slides[0]->internalUrl->fill("node/$nid");

        // Make sure the basic page is in the suggestions.
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $suggestions = $autocomplete->getSuggestions();
        $this->assertContains("$title (node/$nid)", $suggestions);

        // Close the modal and save the page so the test can logout.
        $add_pane_modal = new AddPaneModal($this);
        $add_pane_modal->close();
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
    }
}
