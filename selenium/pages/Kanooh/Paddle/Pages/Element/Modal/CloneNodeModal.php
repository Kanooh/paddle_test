<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Modal\CloneNodeModal.
 */

namespace Kanooh\Paddle\Pages\Element\Modal;

/**
 * Class CloneNodeModal.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonCancel
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonConfirm
 */
class CloneNodeModal extends Modal
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'buttonCancel':
                return $this->getWebdriverElement()->byXPath('.//input[@type="submit"][@value="Cancel"]');
            case 'buttonConfirm':
                return $this->getWebdriverElement()->byXPath('.//input[@type="submit"][@value="Confirm"]');
        }

        throw new \Exception("The property $name is undefined.");
    }
}
