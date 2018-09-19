<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\SignupFormPanelsContentTypeForm.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButton;

/**
 * Class representing the Signup Form pane form.
 *
 * @property RadioButton[] $signupForms
 *   The radio buttons to select a signup form, keyed by entity id.
 */
class SignupFormPanelsContentTypeForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'signupForms':
                $criteria = $this->element->using('xpath')->value('.//input[@type="radio"][@name="signup_form"]');
                $elements = $this->element->elements($criteria);
                $buttons = array();

                /* @var \PHPUnit_Extensions_Selenium2TestCase_Element $element */
                foreach ($elements as $element) {
                    // Key the array by entity id so we can easily target them.
                    $buttons[$element->attribute('value')] = new RadioButton($this->webdriver, $element);
                }
                return $buttons;
        }
        throw new FormFieldNotDefinedException($name);
    }
}
