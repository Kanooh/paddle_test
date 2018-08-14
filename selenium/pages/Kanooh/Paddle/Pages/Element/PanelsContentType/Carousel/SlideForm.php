<?php

/**
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\Carousel\SlideForm.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\Carousel;

use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Scald\ImageAtomField;
use Kanooh\Paddle\Pages\Element\Scald\LibraryModal;

/**
 * Represents a slide in the carousel configuration form.
 *
 * @property Text $caption
 *   The form element representing the caption text field.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $dragHandle
 *   Handle to drag the slide up or down.
 * @property ImageAtomField $image
 *   Atom field to select the slide's image.
 * @property RadioButton $urlTypeNoLink
 *   The radio button with value "no link".
 * @property RadioButton $urlTypeInternalLink
 *   The radio button with value "internal link".
 * @property RadioButton $urlTypeExternalLink
 *   The radio button with value "external link"
 * @property AutoCompletedText $internalUrl
 *   The autocomplete field to enter the "internal link".
 * @property Text $externalUrl
 *   The text field to enter the "external link".
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $removeButton
 *   Button to remove the slide.
 * @property string $uuid
 *   The slide's uuid.
 */
class SlideForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        $uuid = $this->element->attribute('data-slide-uuid');
        switch ($name) {
            case 'caption':
                $uuid = $this->element->attribute('data-slide-uuid');
                return new Text($this->webdriver, $this->webdriver->byName('slides[list][' . $uuid . '][caption]'));
            case 'dragHandle':
                $criteria = $this->element->using('xpath')->value('.//a[contains(@class, "tabledrag-handle")]');
                $handle = $this->element->element($criteria);
                return $handle;
            case 'image':
                $criteria = $this->element->using('xpath')->value('.//td[contains(@class, "image")]//div[contains(@class, "paddle-scald-atom-field")]');
                $image = $this->element->element($criteria);
                return new ImageAtomField($this->webdriver, $image);
            case 'urlTypeNoLink':
                $criteria = $this->element->using('xpath')->value('.//input[@value="no_link"]');
                return new RadioButton($this->webdriver, $this->element->element($criteria));
            case 'urlTypeInternalLink':
                $criteria = $this->element->using('xpath')->value('.//input[@value="internal"]');
                return new RadioButton($this->webdriver, $this->element->element($criteria));
            case 'urlTypeExternalLink':
                $criteria = $this->element->using('xpath')->value('.//input[@value="external"]');
                return new RadioButton($this->webdriver, $this->element->element($criteria));
            case 'internalUrl':
                return new AutoCompletedText($this->webdriver, $this->webdriver->byName('slides[list][' . $uuid . '][internal_url]'));
            case 'externalUrl':
                return new Text($this->webdriver, $this->webdriver->byName('slides[list][' . $uuid . '][external_url]'));
            case 'removeButton':
                $criteria = $this->element->using('xpath')->value('.//td[contains(@class, "remove")]/input[@type="submit"]');
                $button = $this->element->element($criteria);
                return $button;
            case 'uuid':
                return $this->element->attribute('data-slide-uuid');
        }
        throw new FormFieldNotDefinedException($name);
    }

    /**
     * Selects an image for the slide.
     *
     * @param int $atom_id
     *   Atom id of the image that has to be selected.
     */
    public function selectImage($atom_id)
    {
        $this->image->clear();
        $this->image->selectButton->click();

        $library_modal = new LibraryModal($this->webdriver);
        $library_modal->waitUntilOpened();
        $atom = $library_modal->library->getAtomById($atom_id);
        $atom->insertLink->click();
        $library_modal->waitUntilClosed();
    }
}
