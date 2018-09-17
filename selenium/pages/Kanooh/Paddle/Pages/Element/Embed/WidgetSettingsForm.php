<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Embed\WidgetSettingsForm.
 */

namespace Kanooh\Paddle\Pages\Element\Embed;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class WidgetSettingsForm
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $saveButton
 *   The form's save button.
 * @property Text $title
 *   The form's title field.
 * @property Text $code
 *   The form's embed code field.
 */
class WidgetSettingsForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'saveButton':
                return $this->element->byXPath('.//input[contains(@id, "edit-save")]');
                break;
            case 'title':
                $element = $this->element->byXPath('.//input[@name="title"]');
                return new Text($this->webdriver, $element);
                break;
            case 'code':
                $element = $this->element->byXPath('.//textarea[@name="embed_code"]');
                return new Text($this->webdriver, $element);
                break;
        }
    }
}
