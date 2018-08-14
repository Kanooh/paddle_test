<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\MailChimp\SignupFormsTableRow.
 */

namespace Kanooh\Paddle\Pages\Element\MailChimp;

use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class representing a row in the table of Signup forms on the Paddle Mailchimp config page.
 *
 * @property string $title
 *   Title of the Signup form.
 * @property string $lists
 *   The lists of the Signup form.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkEdit
 *   The link to edit the Signup Form.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkDelete
 *   The link to delete the Signup Form.
 * @property string $signupFormId
 *   The entity ID of the Signup form.
 */
class SignupFormsTableRow extends Row
{

    /**
     * Construct a new SignupFormsTableRow.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param string $xpath_selector
     *   The XPath selector for this table row.
     */
    public function __construct(WebDriverTestCase $webdriver, $xpath_selector)
    {
        parent::__construct($webdriver);

        $this->xpathSelector = $xpath_selector;
    }

    /**
     * Magic getter for the Signup form table row's properties.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'title':
                $cell = $this->webdriver->byXPath($this->xpathSelector . '//td[contains(@class, "views-field-title")]');
                return $cell->text();
                break;
            case 'lists':
                $cell = $this->webdriver->byXPath($this->xpathSelector . '//td[contains(@class, "views-field-signup-lists")]');
                return $cell->text();
                break;
            case 'linkEdit':
                return $this->webdriver->byXPath($this->xpathSelector . '//a[contains(@class, "signup-form-edit-link")]');
            case 'linkDelete':
                return $this->webdriver->byXPath($this->xpathSelector . '//a[contains(@class, "signup-form-delete-link")]');
            case 'signupFormId':
                $row = $this->webdriver->byXPath($this->xpathSelector);
                return $row->attribute('data-signup-form-id');
                break;
        }
        trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name, E_USER_NOTICE);
    }
}
