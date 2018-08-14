<?php

/**
 * @file
 * Contains the Kanooh\Paddle\Pages\Element\PanelsContentType\Section\Top class.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\Section;

/**
 * Top section of a pane.
 * @package Kanooh\Paddle\Pages\Element\PanelsContentType\Section
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $enable
 *   The enable checkbox.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $typeImage
 *   The 'image' radio button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $typeText
 *   The 'text' radio button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $text
 *   The 'text' textfield.
 */
class Top
{
    /**
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     *   The wrapper element of the top section.
     */
    protected $element;

    /**
     * Constructor.
     *
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The wrapper element of the top section.
     */
    public function __construct(\PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->element = $element;
    }

    /**
     * Magic getter.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'enable':
                return $this->element->byXPath('.//input[@name="top[enable_section]"]');
            case 'typeImage':
                return $this->element->byXPath('.//input[@id="edit-top-section-wrapper-section-content-type-image"]');
            case 'typeText':
                return $this->element->byXPath('.//input[@id="edit-top-section-wrapper-section-content-type-text"]');
            case 'typeTitle':
                return $this->element->byXPath('.//input[@id="edit-top-section-wrapper-section-content-type-title"]');
            case 'text':
                return $this->element->byXPath('.//input[@name="top[section_wrapper][section_text]"]');
        }
    }
}
