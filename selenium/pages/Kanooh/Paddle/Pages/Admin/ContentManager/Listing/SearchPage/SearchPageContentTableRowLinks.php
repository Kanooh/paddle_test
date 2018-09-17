<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPageContentTableRowLinks.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage;

use Kanooh\Paddle\Pages\Element\Links\Links;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The action links in a content discovery tab content table row.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkArchive
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkClone
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkDelete
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkAdminView
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkFrontView
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkPageLayout
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkPageProperties
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkTitle
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkTranslate
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $groupingDropdown
 */
class SearchPageContentTableRowLinks extends Links
{
    /**
     * The XPath selector for the table row that contains the links.
     *
     * @var string
     */
    protected $rowXPath;

    /**
     * Constructs a SearchPageContentTableRowLinks object.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param string $table_row_xpath
     *   The XPath selector for the table row that contains the links.
     */
    public function __construct(WebDriverTestCase $webdriver, $table_row_xpath)
    {
        parent::__construct($webdriver);
        $this->rowXPath = $table_row_xpath;
    }

    /**
     * {@inheritdoc}
     */
    public function linkInfo()
    {
        return array(
            'Archive' => array(
                'xpath' => $this->rowXPath . '//td//a[contains(@class, "ui-icon-archive")]',
            ),
            'Clone' => array(
                'xpath' => $this->rowXPath . '//td//a[contains(@class, "ui-icon-clone")]',
            ),
            'Title' => array(
                'xpath' => $this->rowXPath . '//td[contains(@class, "views-field-title")]/a',
            ),
            'PageProperties' => array(
                'xpath' => $this->rowXPath . '//td/a[contains(@class, "ui-icon-edit-page-properties")]',
            ),
            'PageLayout' => array(
                'xpath' => $this->rowXPath . '//td/a[contains(@class, "ui-icon-edit-page-layout")]',
            ),
            'Translate' => array(
                'xpath' => $this->rowXPath . '//td//a[contains(@class, "fa-globe")]',
            ),
            'AdminView' => array(
                'xpath' => $this->rowXPath . '//td//a[contains(@class, "ui-icon-visit-be")]',
            ),
            'FrontView' => array(
                'xpath' => $this->rowXPath . '//td//a[contains(@class, "ui-icon-visit-fe")]',
            ),
            'Delete' => array(
                'xpath' => $this->rowXPath . '//td//a[contains(@class, "ui-icon-delete")]',
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        $grouping_button = $this->rowXPath . '//div[contains(@class, "content-search-actions-grouping")]';
        // If the property starts with 'link...' then return the matching link.
        if (strpos($name, 'link') === 0) {
            $link_name = substr($name, 4);
            // Special case - the following links are grouped in a dropdown.
            if (in_array($link_name, array('Clone', 'Archive', 'Translate', 'AdminView', 'FrontView'))) {
                // Click on the grouping button will leave it open even if
                // Selenium moves the mouse out of it.
                $this->groupingDropdown->click();

                // Wait until the dropdown is opened.
                $xpath = $grouping_button . '/ul[contains(@class, "content-manager-more-actions")]';
                $this->webdriver->waitUntilElementIsPresent($xpath);

                // Move to the link just in case it's not visible.
                $link_info = $this->linkInfo();
                $link_xpath = $link_info[$link_name]['xpath'];
                $this->webdriver->waitUntilElementIsPresent($link_xpath);
                $element = $this->webdriver->byXPath($link_xpath);
                $this->webdriver->moveto($element);

                return $element;
            }
            return $this->link($link_name);
        } elseif ($name == 'groupingDropdown') {
            return $this->webdriver->byXPath($grouping_button);
        }

        trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name, E_USER_NOTICE);

        return null;
    }
}
