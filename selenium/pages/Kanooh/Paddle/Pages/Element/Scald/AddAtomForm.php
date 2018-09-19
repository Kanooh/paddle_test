<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Scald\AddAtomForm.
 */

namespace Kanooh\Paddle\Pages\Element\Scald;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;

/**
 * Form to add a new movie file.
 *
 * @property PluploadFileList $fileList
 *   The Plupload box containing the files to upload.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $continueButton
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $cancelButton
 */
class AddAtomForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'fileList':
                $element = $this->element->byXPath('.//div[@id = "edit-file_container"]');

                return new PluploadFileList($this->webdriver, $element);
            case 'continueButton':
                return $this->element->byXPath('.//input[@value="Continue"]');
            case 'cancelButton':
                return $this->element->byXPath('.//input[@value="Cancel"]');
        }

        throw new FormFieldNotDefinedException($name);
    }
}
