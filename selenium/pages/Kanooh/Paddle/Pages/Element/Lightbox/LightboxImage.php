<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Lightbox\LightboxImage.
 */

namespace Kanooh\Paddle\Pages\Element\Lightbox;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * A class representing a image for which Lightbox was enabled.
 */
class LightboxImage extends Element
{
    /**
     * Constructs an LightboxImage object.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param string $id
     *   The id of the image atom.
     */
    public function __construct(WebDriverTestCase $webdriver, $id)
    {
        parent::__construct($webdriver);
        $this->xpathSelector = '//a[contains(@class, "colorbox-link")]/img[contains(@class, "atom-id-' . $id . '")]';
    }

    /**
     * Opens the lightbox with the image.
     *
     * @return LightboxModal
     *   The lightbox modal.
     */
    public function openLightbox()
    {
        $this->getWebdriverElement()->click();
        $modal = new LightboxModal($this->webdriver);
        $modal->waitUntilOpened();
        return $modal;
    }
}
