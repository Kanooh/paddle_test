<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Form\Checkboxes.
 */

namespace Kanooh\Paddle\Pages\Element\Form;

use Kanooh\WebDriver\WebDriverTestCase;

/**
 * A form field representing multiple checkboxes with the same base name.
 *
 * It maps the 'checkboxes' Drupal element type.
 *
 * @package Kanooh\Paddle\Pages\Element\Form
 */
class Checkboxes extends FormField
{
    /**
     * @var Checkbox[]
     *   The checkboxes elements found.
     */
    protected $checkboxes = array();

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        parent::__construct($webdriver, $element);

        $elements = $this->element->elements($this->element->using('xpath')->value('.//input[@type="checkbox"]'));
        if (!empty($elements)) {
            foreach ($elements as $value) {
                $checkbox = new Checkbox($this->webdriver, $value);
                $this->checkboxes[$checkbox->getValue()] = $checkbox;
            }
        }
    }

    /**
     * Returns the count of checkboxes found.
     *
     * @return int
     *   The number of checkboxes children of the main element.
     */
    public function count()
    {
        return count($this->checkboxes);
    }

    /**
     * Returns a Checkbox instance fetched by value.
     *
     * @param string $value
     *   The value property of the checkbox.
     *
     * @return Checkbox|bool
     *   False if checkbox was not found, the Checkbox instance otherwise.
     */
    public function getByValue($value)
    {
        return isset($this->checkboxes[$value]) ? $this->checkboxes[$value] : false;
    }

    /**
     * Returns all the checkboxes found, keyed by checkbox value.
     *
     * @return Checkbox[]
     *   An array of Checkbox classes representing the checkboxes.
     */
    public function getAll()
    {
        return $this->checkboxes;
    }

    /**
     * Returns all the checkboxes that are checked.
     *
     * @return array
     */
    public function getChecked()
    {
        return array_filter($this->checkboxes, function ($checkbox) {
            /* @var Checkbox $checkbox */
            return $checkbox->isChecked();
        });
    }
}
