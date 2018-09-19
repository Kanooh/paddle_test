<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\MailChimp\ApiKeyModal.
 */

namespace Kanooh\Paddle\Pages\Element\MailChimp;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Class ApiKeyModal
 *
 * @property ApiKeyForm $form
 */
class ApiKeyModal extends Modal
{
    public function __get($name)
    {
        switch ($name) {
            case 'form':
                return new ApiKeyForm($this->webdriver, $this->webdriver->byXPath('//form[contains(@id, "paddle-mailchimp-edit-api-key-form")]'));
        }
    }
}
