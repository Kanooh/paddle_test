<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\PhotoAlbum\PhotoAlbumPane.
 */

namespace Kanooh\Paddle\Pages\Element\Pane\PhotoAlbum;

use Kanooh\Paddle\Pages\Element\Lightbox\LightboxImage;
use Kanooh\Paddle\Pages\Element\Pane\Pane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\PhotoAlbum\PhotoAlbumPanelsContentType;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for a Panels pane with Ctools content type 'Photo album'.
 *
 * @property LightboxImage[] $images
 */
class PhotoAlbumPane extends Pane
{
    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//div[contains(@class, "pane-photo-album")]';

    /**
     * @var PhotoAlbumPanelsContentType
     */
    public $contentType;

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver, $uuid, $xpath_selector = '')
    {
        parent::__construct($webdriver, $uuid, $xpath_selector);

        $this->contentType = new PhotoAlbumPanelsContentType($this->webdriver);
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'images':
                $images = array();

                $xpath = '//div[contains(@class, "pane-photo-album")]//div[contains(@class, "photo-album-image")]/a/img';
                $criteria = $this->webdriver->using('xpath')->value($xpath);
                $elements = $this->webdriver->elements($criteria);

                /* @var \PHPUnit_Extensions_Selenium2TestCase_Element $element */
                foreach ($elements as $element) {
                    $class = $element->attribute('class');
                    $classes = explode('-', $class);
                    $sid = $classes[2];

                    $image = new LightboxImage($this->webdriver, $sid);
                    $images[$sid] = $image;
                }

                return $images;

                break;
        }

        throw new \Exception("Property with name $name not defined");
    }
}
