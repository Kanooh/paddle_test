<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PaneCollection\PaneCollectionDeleteModal.
 */

namespace Kanooh\Paddle\Pages\Element\PaneCollection;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Class PaneCollectionDeleteModal
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $confirmButton
 *   Confirm (delete) button.
 */
class PaneCollectionDeleteModal extends Modal
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
