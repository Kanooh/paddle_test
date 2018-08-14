<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\AppsOverviewPage\InstallPaddletModal.
 */

namespace Kanooh\Paddle\Pages\Element\AppsOverviewPage;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Class UninstallPaddletModal
 *
 * @property InstallPaddletForm $form
 */
class InstallPaddletModal extends Modal
{
    public function __get($name)
    {
        switch ($name) {
            case 'form':
                return new InstallPaddletForm($this->webdriver, $this->webdriver->byXPath('//form[contains(@id, "paddle-apps-confirm-activation")]'));
        }
    }
}
