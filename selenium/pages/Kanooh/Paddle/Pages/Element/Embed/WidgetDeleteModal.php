<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Embed\WidgetDeleteModal.
 */

namespace Kanooh\Paddle\Pages\Element\Embed;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Class WidgetDeleteModal
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $cancelButton
 *   Cancel button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $confirmButton
 *   Confirm (delete) button.
 */
class WidgetDeleteModal extends Modal
{
    public function __get($name)
    {
        switch ($name) {
            case 'buttonCancel':
                $xpath = $this->getXPathSelector() . '//input[@type="submit"][@value="Cancel"]';
                return $this->webdriver->byXPath($xpath);
            case 'buttonConfirm':
                $xpath = $this->getXPathSelector() . '//input[@type="submit"][@value="Delete"]';
                return $this->webdriver->byXPath($xpath);
        }
    }
}
