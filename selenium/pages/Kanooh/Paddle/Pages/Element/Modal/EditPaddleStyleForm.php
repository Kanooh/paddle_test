<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Modal\EditPaddleStyleForm.
 */

namespace Kanooh\Paddle\Pages\Element\Modal;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\RadioButton;

/**
 * The main form of the Paddle Style modal.
 *
 * @property RadioButton[] $subPaletteRadios
 *   The radio buttons allowing to change the Paddle Style for this pane.
 */
class EditPaddleStyleForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'subPaletteRadios':
                $radios = array();

                $xpath = './/div[@id = "paddle-style-plugin-instance-color-subpalettes"]'
                    . '//input[contains(@class, "paddle-color-palettes-image-radios")]';
                $elements = $this->element->elements($this->element->using('xpath')->value($xpath));
                /* @var \PHPUnit_Extensions_Selenium2TestCase_Element[] $elements */
                foreach ($elements as $element) {
                    $radios[$element->value()] = new RadioButton($this->webdriver, $element);
                }

                return $radios;
        }

        return false;
    }
}
