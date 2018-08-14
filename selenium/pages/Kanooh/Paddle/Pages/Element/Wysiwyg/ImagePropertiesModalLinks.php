<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Wysiwyg\ImagePropertiesModalLinks.
 */

namespace Kanooh\Paddle\Pages\Element\Wysiwyg;

use Kanooh\Paddle\Pages\Element\Links\Links;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The action links in the Image Properties modal.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkImageInfo
 *   The 'Image Info' tab.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkLink
 *   The 'Link' tab.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkAdvanced
 *   The 'Advanced' tab.
 */
class ImagePropertiesModalLinks extends Links
{
    /**
     * The XPath selector for the modal that contains the links.
     *
     * @var string
     */
    protected $modalXPathSelector;

    /**
     * Constructs an ImagePropertiesModalLinks object.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param string $modal_xpath_selector
     *   The XPath selector for the modal that contains the links.
     */
    public function __construct(WebDriverTestCase $webdriver, $modal_xpath_selector)
    {
        parent::__construct($webdriver);

        $this->modalXPathSelector = $modal_xpath_selector;
    }

    /**
     * {@inheritdoc}
     */
    public function linkInfo()
    {
        return array(
            'ImageInfo' => array(
                'xpath' => $this->modalXPathSelector . '//a[@title = "Image Info"]',
            ),
            'Link' => array(
                'xpath' => $this->modalXPathSelector . '//a[@title = "Link"]',
            ),
            'Advanced' => array(
                'xpath' => $this->modalXPathSelector . '//a[@title = "Advanced"]',
            ),
        );
    }
}
