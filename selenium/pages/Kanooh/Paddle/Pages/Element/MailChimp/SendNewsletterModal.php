<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\MailChimp\SendNewsletterModal.
 */

namespace Kanooh\Paddle\Pages\Element\MailChimp;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * The modal, used to send a test newsletter e-mail or campaigns.
 *
 * @property SendNewsletterForm $form
 */
class SendNewsletterModal extends Modal
{

    /**
     * {inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'form':
                $xpath = '//form[contains(@id, "paddle-mailchimp-send-newsletter-form")]';
                return new SendNewsletterForm($this->webdriver, $this->webdriver->byXPath($xpath));
        }
    }
}
