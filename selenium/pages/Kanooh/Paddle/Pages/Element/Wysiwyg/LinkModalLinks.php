<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Wysiwyg\LinkModalLinks.
 */

namespace Kanooh\Paddle\Pages\Element\Wysiwyg;

use Kanooh\Paddle\Pages\Element\Links\Links;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The action links in the Link modal.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkLinkInfo
 *   The 'Link Info' tab.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkTarget
 *   The 'Target' tab.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkAdvanced
 *   The 'Advanced' tab.
 */
class LinkModalLinks extends Links
{
    /**
     * The XPath selector for the modal that contains the links.
     *
     * @var string
     */
    protected $modalXPathSelector;

    /**
     * Constructs a LinkModalLinks object.
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
            'LinkInfo' => array(
                'xpath' => $this->modalXPathSelector . '//a[@title = "Link Info"]',
            ),
            'Target' => array(
                'xpath' => $this->modalXPathSelector . '//a[@title = "Target"]',
            ),
            'Advanced' => array(
                'xpath' => $this->modalXPathSelector . '//a[@title = "Advanced"]',
            ),
        );
    }
}
