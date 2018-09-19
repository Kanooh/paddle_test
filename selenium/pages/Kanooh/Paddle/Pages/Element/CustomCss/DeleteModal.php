<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\CustomCss\DeleteModal.
 */

namespace Kanooh\Paddle\Pages\Element\CustomCss;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * The delete modal, used to confirm the deletion of a context.
 *
 * @property DeleteForm $form
 */
class DeleteModal extends Modal
{
    /**
     * @var string
     */
    protected $formXPathSelector = '//form[@id="paddle-custom-css-delete-context-form"]';

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
                return new DeleteForm($this->webdriver, $this->webdriver->byXPath($this->formXPathSelector));
        }
    }
}
