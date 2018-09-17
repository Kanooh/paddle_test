<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\XMLSiteMap\SiteMapLinksTableRow.
 */

namespace Kanooh\Paddle\Pages\Element\XMLSiteMap;

use Kanooh\Paddle\Pages\Element\ElementNotPresentException;
use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class SiteMapLinksTableRow
 *
 * @property string $language
 * @property string $link
 */
class SiteMapLinksTableRow extends Row
{
    /**
     * The webdriver element of the site map links row.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Construct a new SiteMapLinksTableRow.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The webdriver element of the site map links table row.
     */
    public function __construct(WebDriverTestCase $webdriver, $element)
    {
        parent::__construct($webdriver);
        $this->element = $element;
    }

    /**
     * Magic getter for the definition's properties.
     */
    public function __get($name)
    {
        $criteria = $this->element->using('xpath')->value('.//td');
        $cells = $this->element->elements($criteria);

        switch ($name) {
            case 'language':
                return $cells[0]->text();
                break;
            case 'link':
                return $cells[1]->text();
                break;
        }
        throw new ElementNotPresentException($name);
    }
}
