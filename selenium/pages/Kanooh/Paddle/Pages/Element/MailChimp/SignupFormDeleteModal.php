<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\MailChimp\SignupFormDeleteModal.
 */

namespace Kanooh\Paddle\Pages\Element\MailChimp;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * The delete modal, used to confirm the deletion of an Signup Form.
 *
 * @property DeleteForm $form
 */
class SignupFormDeleteModal extends Modal
{
    /**
     * XPath selector of the form element.
     *
     * @var string
     */
    protected $formXPathSelector = '//form[@id="paddle-mailchimp-delete-mailchimp-signup-form"]';

    /**
     * Makes the browser wait until the modal is fully loaded.
     *
     * This is determined by the fact that the form is displayed.
     */
    public function waitUntilOpened()
    {
        $this->webdriver->waitUntilElementIsDisplayed($this->formXPathSelector);

        // Store the modal's unique ids for later use.
        $this->getUniqueIds();
    }

    /**
     * {inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'form':
                return new SignupFormDeleteForm($this->webdriver, $this->webdriver->byXPath($this->formXPathSelector));
        }
    }
}
