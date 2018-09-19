<?php
/**
 * @file
 */

namespace Kanooh\Paddle\Pages\Element\Scald\Image;

use Kanooh\Paddle\Pages\Element\Form\FileField;
use Kanooh\Paddle\Pages\Element\Form\Select;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Scald\AddOptionsFormBase;

/**
 * Class AddOptionsForm
 * @package Kanooh\Paddle\Pages\Element\Scald\Image
 *
 * @property Text $title
 * @property Text $description
 * @property Text $alternativeText
 * @property Select $manualCropSelect
 * @property FileField $image
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $thumbnail
 */
class AddOptionsForm extends AddOptionsFormBase
{
    public function __get($name)
    {
        switch ($name) {
            case 'title':
                $element = $this->element->byXPath('.//input[@name="atom0[title]"]');

                return new Text($this->webdriver, $element);
            case 'description':
                $element = $this->element->byXPath('.//textarea[@name="atom0[field_scald_description][und][0][value]"]');

                return new Text($this->webdriver, $element);
            case 'alternativeText':
                $element = $this->element->byXPath('.//input[@name="atom0[field_scald_alt_tag][und][0][value]"]');

                return new Text($this->webdriver, $element);
            // This field is no longer used. But it might be reintroduced.
            // case 'caption':
            //    $element = $this->element->byXPath('.//input[@name="atom0[field_scald_caption][und][0][value]"]');
            //    return new Text($this->webdriver, $element);
            case 'manualCropSelect':
                $element = $this->element->byXPath('.//select[contains(@class, "manualcrop-style-select")]');

                return new Select($this->webdriver, $element);
            case 'image':
                return new FileField(
                    $this->webdriver,
                    '//input[@name="files[atom0_scald_thumbnail_und_0]"]',
                    '//input[@name="atom0_scald_thumbnail_und_0_upload_button"]',
                    '//input[@name="atom0_scald_thumbnail_und_0_remove_button"]'
                );
            case 'thumbnail':
                return $this->element->byXPath('.//div[contains(@class, "image-preview")]//img');
        }

        return parent::__get($name);
    }

    /**
     * Adds a cropped style for an image edited in the form.
     *
     * @param $machine_name
     *   The machine name of the image style option we want to crop.
     */
    public function setCroppedStyle($machine_name)
    {
        $overlay_xpath = '//body/div[contains(@class, "manualcrop-overlay")]';

        $this->manualCropSelect->selectOptionByValue($machine_name);
        $manual_crop_overlay = new ManualCropOverlay($this->webdriver, $this->webdriver->byXPath($overlay_xpath));
        $manual_crop_overlay->waitUntilLoaded();
        $manual_crop_overlay->saveButton->click();
    }

    /**
     * Finds out which of the image styles for the image we are editing are cropped.
     *
     * @return array
     *   Array containing the machine names of the cropped image styles.
     */
    public function getCroppedStyles()
    {
        $cropped_styles = array();
        $options = $this->manualCropSelect->getOptions();
        foreach ($options as $value => $text) {
            if (strpos($text, '(cropped)') !== false) {
                $cropped_styles[] = $value;
            }
        }

        return $cropped_styles;
    }
}
