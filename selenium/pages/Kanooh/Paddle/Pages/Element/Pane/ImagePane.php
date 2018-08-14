<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\ImagePane.
 */

namespace Kanooh\Paddle\Pages\Element\Pane;

use Kanooh\Paddle\Pages\Element\PanelsContentType\ImagePanelsContentType;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for a Panels pane with Ctools content type 'Image'.
 */
class ImagePane extends Pane
{

    /**
     * The object for the pane content type.
     *
     * @var ImagePanelsContentType
     */
    public $contentType;

    /**
     * The main image of the pane (its main content).
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public $mainImage;

    /**
     * Constructs an ImagePane.
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

        $this->contentType = new ImagePanelsContentType($this->webdriver);

        // Get the main image. It is mandatory so no Image pane can exist
        // without a main image.
        $this->mainImage = $this->webdriver->byXPath($this->xpathSelector . '//div[contains(@class, "pane-section-body")]//img');
    }

    /**
     * Checks if the image with the passed filename is present in the pane.
     *
     * @param string $filename
     *   The filename of the image that is supposed to be in the pane without file extension.
     *
     * @return bool
     *   True if the image was found, false otherwise.
     */
    public function checkImageDisplayedInPane($filename)
    {
        return strpos($this->mainImage->attribute('src'), $filename) !== false;
    }

    /**
     * Checks if the caption HTML is rendered.
     *
     * @param string $caption_text
     *   The text to search for.
     *
     * @return bool
     *   Return true if the text is found, false otherwise.
     */
    public function checkCaption($caption_text)
    {
        $xpath = '//figure//figcaption[@class="image-pane-caption" and contains(., "' . $caption_text . '")]';
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
        return count($elements) ? (bool) $elements[0] : false;
    }

    /**
     * Checks if the link on the main image has the passed href.
     *
     * @param string $href
     *   The href which the link should have.
     *
     * @return bool
     *   True if the link has the expected href, false otherwise.
     */
    public function checkImageLink($href)
    {
        $xpath = $this->xpathSelector . '//a[@href="' . $href . '"]//img';
        try {
            $this->webdriver->byXPath($xpath);
            return true;
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            return false;
        }
    }
}
