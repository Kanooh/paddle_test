<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Modal\StylePaneModal.
 */

namespace Kanooh\Paddle\Pages\Element\Modal;

use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Scald\ImageAtomField;

/**
 * The modal that allows to edit a pane on a Panels display editor page.
 *
 * @property PaneStyleSubpaletteRadioButtons $subPaletteRadios
 * @property PaneStyleRegionStyleRadioButtons $regionStyle
 * @property ImageAtomField $backgroundImage
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $saveButton
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $options
 */
class StylePaneModal extends Modal
{
    /**
     * {@inheritdoc}
     */
    protected $submitButtonXPathSelector = '//input[@id="edit-submit"]';

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'subPaletteRadios':
                return new PaneStyleSubpaletteRadioButtons($this->webdriver, $this->webdriver->byId('edit-color-subpalettes-paddle-color-subpalette'));
            case 'regionStyle':
                return new PaneStyleRegionStyleRadioButtons($this->webdriver, $this->webdriver->byId('edit-style'));
            case 'backgroundImage':
                return new ImageAtomField(
                    $this->webdriver,
                    $this->webdriver->byXPath('.//div/input[@name="settings[image][sid]"]/..')
                );
                break;
            case 'saveButton':
                return $this->webdriver->byId('edit-submit--2');
            case 'options':
                return $this->webdriver->byXPath('//div[@class="paddle-color-palettes-option"]');
        }
        throw new FormFieldNotDefinedException($name);
    }
}
