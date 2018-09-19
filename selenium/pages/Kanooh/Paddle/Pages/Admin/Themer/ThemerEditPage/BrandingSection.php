<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\BrandingSection.
 */

namespace Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage;

use Kanooh\Paddle\Pages\Element\ColorPicker\ColorPicker;

/**
 * Class for the Branding section in the Paddle Themer form.
 *
 * @package Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage
 *
 * @property ColorPaletteRadioButtons $colorPaletteRadios
 *   The radio buttons for the color palettes in the Paddle Themer.
 */
class BrandingSection extends Section
{
    public function __get($name)
    {
        switch ($name) {
            case 'colorPaletteRadios':
                $element = $this->webdriver->byId('paddle-style-plugin-instance-color-palettes');
                return new ColorPaletteRadioButtons($this->webdriver, $element);
        }

        return parent::__get($name);
    }

    /**
     * Returns all the color boxes in the label of a color palette radio button.
     *
     * @param $palette_name
     *   The machine name of the palette for which we want the colors.
     *
     * @return ColorPaletteColorBoxes
     */
    public function getColorBoxesForPalette($palette_name)
    {
        $xpath = '//input[@value="' . $palette_name .
            '"]/../label/div[contains(@class, "paddle-color-palettes-option")]';
        return new ColorPaletteColorBoxes($this->webdriver, $this->webdriver->byXPath($xpath));
    }

    /**
     * Finds the displayed and active color picker modal and returns the object.
     *
     * @return ColorPicker|null
     */
    public function getActiveColorPicker()
    {
        $xpath = '//body/div[contains(@class, "colorpicker")]';
        $elements = $this->element->elements($this->element->using('xpath')->value($xpath));
        /* @var \PHPUnit_Extensions_Selenium2TestCase_Element[] $elements */
        foreach ($elements as $element) {
            $style = explode(';', str_replace(' ', '', $element->attribute('style')));
            if (in_array('display:block', $style)) {
                return new ColorPicker($this->webdriver, $element);
            }
        }

        return null;
    }
}
