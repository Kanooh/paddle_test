<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Wysiwyg\Table\TablePropertiesModalLinks.
 */

namespace Kanooh\Paddle\Pages\Element\Wysiwyg\Table;

use Kanooh\Paddle\Pages\Element\Links\Links;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The action links in the Table Properties modal.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkTableProperties
 *   The 'Table Properties' tab.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkAdvanced
 *   The 'Advanced' tab.
 */
class TablePropertiesModalLinks extends Links
{
    /**
     * The XPath selector for the modal that contains the links.
     *
     * @var string
     */
    protected $modalXPathSelector;

    /**
     * Constructs an TablePropertiesModalLinks object.
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
            'TableProperties' => array(
                'xpath' => $this->modalXPathSelector . '//a[@title = "Table Properties"]',
            ),
            'Advanced' => array(
                'xpath' => $this->modalXPathSelector . '//a[@title = "Advanced"]',
            ),
        );
    }
}
