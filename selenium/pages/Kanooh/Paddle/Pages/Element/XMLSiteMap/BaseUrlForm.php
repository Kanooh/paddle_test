<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\XMLSiteMap\BaseUrlForm.
 */

namespace Kanooh\Paddle\Pages\Element\XMLSiteMap;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class BaseUrlForm
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $saveButton
 *   The form's save button.
 * @property Text $baseUrl
 *   The form's base URL field.
 */
class BaseUrlForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'saveButton':
                return $this->element->byXPath('.//input[contains(@id, "edit-save")]');
                break;
            case 'baseUrl':
                $element = $this->element->byXPath('.//input[@name="base_url"]');
                return new Text($this->webdriver, $element);
                break;
        }
    }
}
