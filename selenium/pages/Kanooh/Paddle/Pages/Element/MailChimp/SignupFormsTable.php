<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\MailChimp\SignupFormsTable.
 */

namespace Kanooh\Paddle\Pages\Element\MailChimp;

use Kanooh\Paddle\Pages\Element\Table\Table;
use Kanooh\WebDriver\WebdriverTestCase;

/**
 * Class representing the table of Signup forms on the Paddle Mailchimp config page.
 */
class SignupFormsTable extends Table
{
    /**
     * The webdriver element of the Signup Forms table.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Construct a new SignupFormsTable.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param string $xpath
     *   The xpath selector of the Signup Forms table instance.
     */
    public function __construct(WebDriverTestCase $webdriver, $xpath)
    {
        parent::__construct($webdriver);
        $this->xpathSelector = $xpath;
        $this->element = $this->webdriver->byXPath($xpath);
    }

    /**
     * Returns a row based on the title given.
     *
     * @param string $title
     *   The title of the Signup form.
     *
     * @return SignupFormsTableRow | null
     *   The row for the given title, or null if not found.
     */
    public function getRowByTitle($title)
    {
        $row_xpath = '//tr/td[contains(@class, "views-field-title") and normalize-space(text())="' . $title . '"]/..';
        $criteria = $this->webdriver->using('xpath')->value($this->xpathSelector . $row_xpath);
        $elements = $this->webdriver->elements($criteria);
        if (count($elements) > 0) {
            return new SignupFormsTableRow($this->webdriver, $this->xpathSelector . $row_xpath);
        }

        return null;
    }

    /**
     * Returns a row based on the Signup Form ID given.
     *
     * @param int $signup_id
     *   The ID of the Signup form.
     *
     * @return SignupFormsTableRow | null
     *   The row for the given ID, or null if not found.
     */
    public function getRowById($signup_id)
    {
        $row_xpath = '//tr[@data-signup-form-id = "' . $signup_id . '"]';
        $criteria = $this->webdriver->using('xpath')->value($this->xpathSelector . $row_xpath);
        $elements = $this->webdriver->elements($criteria);
        if (count($elements) > 0) {
            return new SignupFormsTableRow($this->webdriver, $this->xpathSelector . $row_xpath);
        }

        return null;
    }
}
