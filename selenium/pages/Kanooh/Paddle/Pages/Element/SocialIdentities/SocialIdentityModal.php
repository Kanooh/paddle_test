<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\SocialIdentities\SocialIdentityModal.
 */

namespace Kanooh\Paddle\Pages\Element\SocialIdentities;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Class representing the Social Identity add/edit modal.
 *
 * @property SocialIdentityForm $form
 */
class SocialIdentityModal extends Modal
{
    public function __get($name)
    {
        switch ($name) {
            case 'form':
                $element = $this->webdriver->byXPath('//form[contains(@id, "paddle-social-identities-settings-form")]');
                return new SocialIdentityForm($this->webdriver, $element);
        }
    }
}
