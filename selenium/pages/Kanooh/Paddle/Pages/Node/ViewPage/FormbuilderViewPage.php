<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\FormbuilderViewPage.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage;

use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * A formbuilder detail page in the frontend view.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $submit
 *   The form submit button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $nextPage
 *   The next page button.
 */
class FormbuilderViewPage extends ViewPage
{
    /**
     * Check if the custom form field is present.
     *
     * @param string $field
     *   The name of the field.
     *
     * @return bool
     *   TRUE if the filter field is present, FALSE otherwise.
     */
    public function checkCustomFormFieldPresent($field)
    {
        $xpath = '//form[contains(@class, "webform-client-form")]'
            . '//div[contains(@class, "webform-component-' . $field . '")]/input[@type="text"]';
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
        return (bool) count($elements);
    }


  /**
   * Gets the requested textfield element.
   *
   * @param string $xpath
   *   The expected xpath for the textfield element.
   *
   * @return \Kanooh\Paddle\Pages\Element\Form\Text
   *   The textfield element.
   */
    public function getTextFieldElement($xpath)
    {
        $element = $this->webdriver->byXPath($xpath);
        return new Text($this->webdriver, $element);
    }

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'submit':
                $xpath = '//form[contains(@class, "webform-client-form")]//input[contains(@class, "webform-submit")]';
                return $this->webdriver->byXPath($xpath);
            case 'nextPage':
                $xpath = '//form[contains(@class, "webform-client-form")]//input[contains(@class, "webform-next")]';
                return $this->webdriver->byXPath($xpath);
        }
        return parent::__get($property);
    }
}
