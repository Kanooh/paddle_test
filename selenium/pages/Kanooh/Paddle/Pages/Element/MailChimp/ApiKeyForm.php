<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\MailChimp\ApiKeyForm.
 */

namespace Kanooh\Paddle\Pages\Element\MailChimp;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class ApiKeyForm
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $saveButton
 *   The form's save button.
 * @property Text $apiKey
 *   The form's API key field.
 */
class ApiKeyForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'saveButton':
                return $this->element->byXPath('.//input[contains(@id, "edit-save")]');
                break;
            case 'apiKey':
                $element = $this->element->byXPath('.//input[@name="api_key"]');
                return new Text($this->webdriver, $element);
                break;
        }
    }
}
