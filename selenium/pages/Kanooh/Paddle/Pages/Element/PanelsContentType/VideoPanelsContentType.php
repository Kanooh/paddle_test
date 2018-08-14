<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\VideoPanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\PanelsContentType\Video\ConfigurationForm;
use Kanooh\Paddle\Utilities\ScaldService;

/**
 * The 'Video' Panels content type.
 */
class VideoPanelsContentType extends SectionedPanelsContentType
{

    /**
     * {@inheritdoc}
     */
    const TYPE = 'add_video';

    /**
     * {@inheritdoc}
     */
    const TITLE = 'Add video';

    /**
     * {@inheritdoc}
     */
    const DESCRIPTION = 'Add video.';

    /**
     * The width of the video.
     *
     * @var int
     */
    public $width;

    /**
     * The height of the video.
     *
     * @var int
     */
    public $height;

    /**
     * Path to the thumbnail image for the video.
     *
     * @var string
     */
    public $thumbnail_path;

    /**
     * Path to the subtitles for the video.
     *
     * @var string
     */
    public $subtitles_path;

    /**
     * The type of the video - "file" or "YouTube".
     *
     * @var string
     */
    public $video_type;

    /**
     * The path to the video file.
     *
     * @var string
     */
    public $video;

    /**
     * XPath selector of the form element.
     */
    public $formElementXPathSelector = '//form[@id="paddle-scald-video-add-video-content-type-edit-form"]';

    /**
     * {@inheritdoc}
     */
    public function fillInConfigurationForm(Element $element = null)
    {
        $form = $this->getForm($element);

        if ($this->video) {
            $videoField = $form->video;

            // If an video already exists, remove it before continuing.
            $videoField->clear();

            $videoField->selectButton->click();

            // Add a new atom to the library, and insert it. The insertAtom
            // method waits for the library to close so we don't have to wait
            // for anything.
            $scald_service = new ScaldService($this->webdriver);
            if ($this->video_type == 'file') {
                $atom_id = $scald_service->addVideoFileToLibraryModal($this->video, $this->width, $this->height, $this->thumbnail_path, $this->subtitles_path);
            } else {
                $atom_id = $scald_service->addYouTubeVideoToLibraryModal($this->video, $this->subtitles_path);
            }
            $scald_service->insertAtom($atom_id);
        }

        $this->fillInSections();
    }

    /**
     * {@inheritdoc}
     */
    public function getForm(Element $element = null)
    {
        $xpath_selector = !empty($element) ? $element->getXPathSelector() : '';

        $form_xpath = $xpath_selector . '//form[@id="paddle-scald-video-add-video-content-type-edit-form"]';

        // Wait until the form is fully loaded, otherwise the test might fail.
        $form_element = $this->webdriver->waitUntilElementIsDisplayed($form_xpath);

        return new ConfigurationForm($this->webdriver, $form_element);
    }
}
