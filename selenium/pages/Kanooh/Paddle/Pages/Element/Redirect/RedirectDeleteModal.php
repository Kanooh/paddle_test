<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Redirect\RedirectDeleteModal.
 */

namespace Kanooh\Paddle\Pages\Element\Redirect;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Class RedirectDeleteModal
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $confirmButton
 *   Confirm (delete) button.
 */
class RedirectDeleteModal extends Modal
{
    public function __get($name)
    {
        switch ($name) {
            case 'confirmButton':
                $xpath = $this->getXPathSelector() . '//input[@value="Delete"]';
                return $this->webdriver->byXPath($xpath);
        }
    }
}
