<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Redirect\RedirectModal.
 */

namespace Kanooh\Paddle\Pages\Element\Redirect;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Class RedirectModal
 *
 * @property RedirectModalSettingsForm $form
 */
class RedirectModal extends Modal
{
    public function __get($name)
    {
        switch ($name) {
            case 'form':
                return new RedirectModalSettingsForm($this->webdriver, $this->webdriver->byXPath('//form[contains(@id, "redirect-edit-form")]'));
        }
    }
}
