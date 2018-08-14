<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Glossary\GlossaryDefinitionDeleteModal.
 */

namespace Kanooh\Paddle\Pages\Element\Glossary;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Class GlossaryDefinitionDeleteModal
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonCancel
 *   Cancel button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonConfirm
 *   Confirm (delete) button.
 */
class GlossaryDefinitionDeleteModal extends Modal
{
    public function __get($name)
    {
        switch ($name) {
            case 'buttonCancel':
                $xpath = $this->getXPathSelector() . '//input[@type="submit" and @value="Cancel"]';
                return $this->webdriver->byXPath($xpath);
            case 'buttonConfirm':
                $xpath = $this->getXPathSelector() . '//input[@type="submit" and @value="Delete"]';
                return $this->webdriver->byXPath($xpath);
        }
    }
}
