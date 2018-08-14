<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Redirect\RedirectImportModal.
 */

namespace Kanooh\Paddle\Pages\Element\Redirect;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Class RedirectImportModal
 *
 * @property RedirectImportModalSettingsForm $form
 */
class RedirectImportModal extends Modal
{
    public function __get($name)
    {
        switch ($name) {
            case 'form':
                $xpath = '//form[contains(@id, "paddle-redirect-import-form")]';
                return new RedirectImportModalSettingsForm($this->webdriver, $this->webdriver->byXPath($xpath));
        }
    }
}
