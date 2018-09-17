<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\Section\TopSectionForm.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\Section;

use Kanooh\Paddle\Pages\Element\Form\AjaxSelect;
use Kanooh\Paddle\Pages\Element\Scald\ImageAtomField;
use Kanooh\Paddle\Utilities\ScaldService;

/**
 * The top section form.
 *
 * @property TopSectionFormContentTypeRadioButtons $contentTypeRadios
 *   The radio buttons to select the content type: 'text' or 'image'.
 * @property ImageAtomField $image
 *   The atom field to select an image.
 * @property ImageAtomField $icon
 *   The atom field to select a top section icon.
 * @property AjaxSelect $imageStyle
 *   The form select element to choose a style for the top section image.
 *   Updates the thumbnail preview automatically, through AJAX.
 */
class TopSectionForm extends SectionForm
{

    const SECTION = 'top';

    /**
     * {@inheritdoc}
     */
    protected $section = self::SECTION;

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'contentTypeRadios':
                return new TopSectionFormContentTypeRadioButtons($this->webdriver, $this->webdriver->byXPath('//div[starts-with(@id, "edit-top-section-wrapper-section-content-type")]'));
            case 'image':
                return new ImageAtomField($this->webdriver, $this->webdriver->byId('edit-top-section-wrapper-section-image-sid-container'));
            case 'icon':
                return new ImageAtomField($this->webdriver, $this->webdriver->byId('edit-top-section-wrapper-icon-image-container'));
            case 'imageStyle':
                $element = $this->webdriver->byName('top[section_wrapper][section_image][style]');
                return new AjaxSelect($this->webdriver, $element);
        }
        return parent::__get($name);
    }

    /**
     * Uploads a new image and selects it.
     *
     * @param string $image
     *   Path of the image to upload and select.
     * @param string $alt_text
     *   The alt text to put if the image is not displayed.
     */
    public function selectNewImage($image, $alt_text = 'Alternative text')
    {
        // The clear method doesn't do anything if the field is already empty.
        $this->image->clear();

        // Click the select button and use the scald service class to upload
        // and insert a new image.
        $this->image->selectButton->click();
        $scald_service = new ScaldService($this->webdriver);
        $atom_id = $scald_service->addImageToLibraryModal($image, $alt_text);

        // The insertAtom method waits for the library to close, so we don't
        // have to.
        $scald_service->insertAtom($atom_id);
    }
}
