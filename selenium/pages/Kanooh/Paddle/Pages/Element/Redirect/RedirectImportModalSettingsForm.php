<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Redirect\RedirectImportModalSettingsForm.
 */

namespace Kanooh\Paddle\Pages\Element\Redirect;

use Kanooh\Paddle\Pages\Element\Form\FileField;
use Kanooh\Paddle\Pages\Element\Form\Form;

/**
 * Class RedirectImportModalSettingsForm
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $importButton
 *   The form's import button.
 * @property FileField $importFile
 *   The form's "import file" field.
 */
class RedirectImportModalSettingsForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'importButton':
                return $this->element->byXPath('.//input[contains(@class, "form-submit")]');
                break;
            case 'importFile':
                return new FileField($this->webdriver, './/input[@name="files[import_file]"]', null, null, null);
        }
    }
}
