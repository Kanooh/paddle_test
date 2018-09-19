<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\EmbedWidgetPanelsContentTypeForm.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButton;

/**
 * Class representing the embed pane form.
 *
 * @property RadioButton[] $widgets
 *   The radio buttons to select a widget, keyed by widget id.
 */
class EmbedWidgetPanelsContentTypeForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'widgets':
                $criteria = $this->element->using('xpath')->value('.//input[@type="radio"][@name="wid"]');
                $elements = $this->element->elements($criteria);
                $buttons = array();

                /* @var \PHPUnit_Extensions_Selenium2TestCase_Element $element */
                foreach ($elements as $element) {
                    // Key the buttons by wid so we can easily target them.
                    $buttons[$element->attribute('value')] = new RadioButton($this->webdriver, $element);
                }
                return $buttons;
        }
        throw new FormFieldNotDefinedException($name);
    }
}
