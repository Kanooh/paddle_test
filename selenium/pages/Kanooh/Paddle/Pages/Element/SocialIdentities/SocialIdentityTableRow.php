<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\SocialIdentities\SocialIdentityTableRow.
 */

namespace Kanooh\Paddle\Pages\Element\SocialIdentities;

use Kanooh\Paddle\Pages\Element\ElementNotPresentException;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Represents a table row in the Social Identity add/edit entity form.
 *
 * @property Text $url
 *   The URL input field in this row.
 * @property Text $title
 *   The title input field in this row.
 */
class SocialIdentityTableRow extends Row
{

    /**
     * The Selenium webdriver element representing the row.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Constructs an SocialIdentityTableRow object.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The Selenium element representing the row.
     */
    public function __construct(WebDriverTestCase $webdriver, $element)
    {
        parent::__construct($webdriver);

        $this->element = $element;
    }

    /**
     * Provides magic properties for the form elements in the row.
     *
     * @param string $name
     *   The name of the form element.
     *
     * @return Text
     *   The form element.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'url':
                $xpath = './/div[contains(@class, "field-value-url")]//input[@type="text"]';
                return new Text($this->webdriver, $this->element->element($this->element->using('xpath')->value($xpath)));
            case 'title':
                $xpath = './/div[contains(@class, "field-value-title")]//input[@type="text"]';
                return new Text($this->webdriver, $this->element->element($this->element->using('xpath')->value($xpath)));
        }
    }
}
