<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\SocialIdentities\SocialIdentitiesDeleteModal.
 */

namespace Kanooh\Paddle\Pages\Element\SocialIdentities;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Class SocialIdentitiesDeleteModal
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonCancel
 *   Cancel button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonConfirm
 *   Confirm (delete) button.
 */
class SocialIdentitiesDeleteModal extends Modal
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
