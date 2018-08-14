<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\VideoPane.
 */

namespace Kanooh\Paddle\Pages\Element\Pane;

use Kanooh\Paddle\Pages\Element\PanelsContentType\VideoPanelsContentType;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for a Panels pane with Ctools content type 'Video'.
 */
class VideoPane extends Pane
{

    /**
     * The object for the pane content type.
     *
     * @var VideoPanelsContentType
     */
    public $contentType;

    /**
     * The main video of the pane (its main content).
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public $mainVideo;

    /**
     * Constructs an VideoPane.
     *
     * @param WebDriverTestCase $webdriver
     *   The webdriver object.
     * @param string $uuid
     *   The uuid of the pane.
     * @param string $pane_xpath_selector
     *   More general xpath selector for the pane.
     */
    public function __construct(WebDriverTestCase $webdriver, $uuid, $pane_xpath_selector)
    {
        parent::__construct($webdriver, $uuid, $pane_xpath_selector);

        $this->contentType = new VideoPanelsContentType($this->webdriver);

        // Get the main video. It is mandatory so no Video pane can exist
        // without a main video.
        $this->mainVideo = $this->webdriver->byXPath($this->xpathSelector . '//div[contains(@class, "pane-section-body")]//video');
    }

    /**
     * Checks that a given video atom is present in the pane.
     *
     * @param array $video_data
     *   Array with the following values:
     *   - string $poster - The filename of the poster.
     *   - string $video_location - The filename/url of the video.
     *   - string $type - The type of the video - "video/mp4" or video/"youtube".
     *   - string $subtitles - The filename of the subtitles.
     *
     * @return bool
     *   True if the video was found, false otherwise.
     */
    public function checkVideoDisplayedInPane($video_data)
    {
        // Check the width and height.
        $style = $this->mainVideo->attribute('style');
        $video_correct = strpos($style, 'max-width: 100%;') !== false;

        // Check the poster.
        $video_correct = $video_correct &&
            (strpos($this->mainVideo->attribute('poster'), $video_data['poster']) !== false);

        // Check the hard-coded attributes.
        $video_correct = $video_correct && ('none' == $this->mainVideo->attribute('preload'));

        // Check the video was processed by Media element.
        $classes = explode(' ', $this->mainVideo->attribute('class'));
        $video_correct = $video_correct && in_array('mediaelement-processed', $classes);

        // Check the source tag.
        $criteria = $this->webdriver->using('xpath')->value('./source');
        $sources = $this->mainVideo->elements($criteria);
        $video_correct = $video_correct && (1 == count($sources));
        $video_correct = $video_correct &&
            (strpos($sources[0]->attribute('src'), $video_data['video_location']) !== false);
        $video_correct = $video_correct && ($video_data['type'] == $sources[0]->attribute('type'));

        // Check the track tag.
        $criteria = $this->webdriver->using('xpath')->value('./track');
        $tracks = $this->mainVideo->elements($criteria);
        if (!empty($video_data['subtitles'])) {
            $video_correct = $video_correct && (1 == count($tracks));
            $video_correct = $video_correct &&
                (strpos($tracks[0]->attribute('src'), $video_data['subtitles']) !== false);
            $video_correct = $video_correct && ('subtitles' == $tracks[0]->attribute('kind'));
        } else {
            $video_correct = $video_correct && (0 == count($tracks));
        }

        return $video_correct;
    }
}
