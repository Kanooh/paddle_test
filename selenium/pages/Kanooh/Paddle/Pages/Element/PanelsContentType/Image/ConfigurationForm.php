<?php
/**
 * @file
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\Image;

use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\AjaxSelect;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Scald\AtomField;

/**
 * Class ConfigurationForm
 * @package Kanooh\Paddle\Pages\Element\PanelsContentType\Image
 *
 * @property AtomField $image
 * @property RadioButton $noLink
 *   The radio button representing the 'no link' url type for the image atom.
 * @property RadioButton $internal
 *   The radio button representing the 'internal' url type for the image atom.
 * @property RadioButton $external
 *   The radio button representing the 'external' url type for the image atom.
 * @property RadioButton $lighbox
 *   The radio button representing the 'Use Lightbox' url type for the image atom.
 * @property Checkbox $showCaption
 *   The form element representing the checkbox to select if we want a caption.
 * @property Text $captionTextArea
 *   The form element representing the textarea to fill out the caption text.
 * @property AjaxSelect $imageStyle
 *   The dropdown to choose the image style from.
 * @property AutoCompletedText $internalUrl
 *   The autocomplete field to enter the "internal link".
 * @property Text $externalUrl
 *   The text field to enter the "External link".
 */
class ConfigurationForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'image':
                $xpath = './/div/input[@name="image_scald[sid]"]/..';
                return new AtomField($this->webdriver, $this->element->byXPath($xpath));
            case 'noLink':
                $xpath = '//fieldset[@id="edit-section-body"]//input[@value = "no_link"]';
                return new RadioButton($this->webdriver, $this->element->byXPath($xpath));
            case 'internal':
                $xpath = '//fieldset[@id="edit-section-body"]//input[@value = "internal"]';
                return new RadioButton($this->webdriver, $this->element->byXPath($xpath));
            case 'external':
                $xpath = '//fieldset[@id="edit-section-body"]//input[@value = "external"]';
                return new RadioButton($this->webdriver, $this->element->byXPath($xpath));
            case 'lightbox':
                $xpath = '//fieldset[@id="edit-section-body"]//input[@value = "colorbox"]';
                return new RadioButton($this->webdriver, $this->element->byXPath($xpath));
            case 'showCaption':
                return new Checkbox($this->webdriver, $this->webdriver->byName('caption_checkbox'));
            case 'captionTextArea':
                return new Text($this->webdriver, $this->webdriver->byName('caption_textarea'));
            case 'imageStyle':
                return new AjaxSelect($this->webdriver, $this->webdriver->byName('image_scald[style]'));
            case 'internalUrl':
                return new AutoCompletedText($this->webdriver, $this->webdriver->byName('internal_url'));
            case 'externalUrl':
                return new Text($this->webdriver, $this->element->byXPath('.//input[@name = "external_url"]'));
        }
    }
}
