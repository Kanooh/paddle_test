<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\Section\SectionForm.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\Section;

use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Base class for the forms of the top and bottom sections.
 *
 * Defines the form fields that are common for both sections.
 *
 * @property Checkbox $enable
 *   The checkbox to enable the section.
 * @property Text $text
 *   The text input field.
 * @property SectionFormUrlTypeRadioButtons $urlTypeRadios
 *   The radio buttons to select the url type: 'noLink', 'internal' or
 *   'external'.
 * @property Text $internalUrl
 *   The internal url input field.
 * @property Text $externalUrl
 *   The external url input field.
 */
abstract class SectionForm extends Form
{

    /**
     * The name of the current section. Either 'top' or 'bottom';
     *
     * @var string
     */
    protected $section;

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'enable':
                return new Checkbox($this->webdriver, $this->webdriver->byName($this->section . '[enable_section]'));
            case 'text':
                return new Text($this->webdriver, $this->webdriver->byName($this->section . '[section_wrapper][section_text]'));
            case 'urlTypeRadios':
                return new SectionFormUrlTypeRadioButtons($this->webdriver, $this->webdriver->byXPath('//div[starts-with(@id, "edit-' . $this->section . '-section-wrapper-section-url-type")]'));
            case 'internalUrl':
                return new AutoCompletedText($this->webdriver, $this->webdriver->byName($this->section . '[section_wrapper][section_internal_url]'));
            case 'externalUrl':
                return new Text($this->webdriver, $this->webdriver->byName($this->section . '[section_wrapper][section_external_url]'));
        }
        throw new FormFieldNotDefinedException($name);
    }
}
