<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\AppsOverviewPage\UninstallPaddletModal.
 */

namespace Kanooh\Paddle\Pages\Element\AppsOverviewPage;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Class UninstallPaddletModal
 *
 * @property UninstallPaddletForm $form
 */
class UninstallPaddletModal extends Modal
{
    public function __get($name)
    {
        switch ($name) {
            case 'form':
                return new UninstallPaddletForm($this->webdriver, $this->webdriver->byId("edit-confirm"));
        }
    }
}
