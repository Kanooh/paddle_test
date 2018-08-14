<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\AppsOverviewPage\InstallPaddletForm.
 */

namespace Kanooh\Paddle\Pages\Element\AppsOverviewPage;

use Kanooh\Paddle\Pages\Element\Form\Form;

/**
 * Class InstallPaddletForm
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $restoreButton
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $cleanInstallButton
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $cancelButton
 */
class InstallPaddletForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'restoreButton':
                return $this->element->byId('edit-restore');
                break;
            case 'cleanInstallButton':
                return $this->element->byId('edit-confirm');
                break;
            case 'cancelButton':
                return $this->element->byId('edit-cancel');
                break;
        }
    }
}
