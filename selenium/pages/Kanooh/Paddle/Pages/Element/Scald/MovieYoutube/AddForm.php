<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Scald\MovieYoutube\AddForm.
 */

namespace Kanooh\Paddle\Pages\Element\Scald\MovieYoutube;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Form to add a new Youtube video.
 *
 * @property Text $url
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $continueButton
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $cancelButton
 */
class AddForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'url':
                return new Text($this->webdriver, $this->element->byXPath('.//input[@name="identifier"]'));
            case 'continueButton':
                return $this->element->byXPath('.//input[@value="Continue"]');
            case 'cancelButton':
                return $this->element->byXPath('.//input[@value="Cancel"]');
        }
    }
}
