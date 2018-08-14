<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Redirect\RedirectModalSettingsForm.
 */

namespace Kanooh\Paddle\Pages\Element\Redirect;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\Select;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class RedirectModalSettingsForm
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $saveButton
 *   The form's save button.
 * @property Text $from
 *   The form's "from" field.
 * @property Text $to
 *   The form's "to" field.
 * @property Select $redirectStatus
 *   The form's "redirect status" select field.
 */
class RedirectModalSettingsForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'saveButton':
                return $this->element->byXPath('.//input[contains(@class, "form-submit")]');
                break;
            case 'from':
                return new Text($this->webdriver, $this->webdriver->byName('source'));
                break;
            case 'to':
                return new Text($this->webdriver, $this->webdriver->byName('redirect'));
                break;
            case 'redirectStatus':
                return new Select($this->webdriver, $this->element->byName('status_code'));
                break;
        }
    }
}
