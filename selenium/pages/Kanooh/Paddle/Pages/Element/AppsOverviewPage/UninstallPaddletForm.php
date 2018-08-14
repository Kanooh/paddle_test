<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\AppsOverviewPage\UninstallPaddletForm.
 */

namespace Kanooh\Paddle\Pages\Element\AppsOverviewPage;

use Kanooh\Paddle\Pages\Element\Form\Form;

/**
 * Class UninstallPaddletForm
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $uninstallButton
 */
class UninstallPaddletForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'uninstallButton':
                return $this->element->byId('edit-confirm');
                break;
        }
    }
}
