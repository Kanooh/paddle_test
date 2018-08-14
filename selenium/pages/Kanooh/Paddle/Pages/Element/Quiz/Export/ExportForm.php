<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Quiz\Export\ExportForm.
 */

namespace Kanooh\Paddle\Pages\Element\Quiz\Export;

use Kanooh\Paddle\Pages\Element\Form\Form;

/**
 * Class ExportForm
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $cancelButton
 *   Cancel button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $exportButton
 *   Export button.
 */
class ExportForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'cancelButton':
                return $this->element->byXPath('.//input[@value="Cancel"]');
            case 'exportButton':
                return $this->element->byXPath('.//input[@value="Export"]');
        }
    }
}
