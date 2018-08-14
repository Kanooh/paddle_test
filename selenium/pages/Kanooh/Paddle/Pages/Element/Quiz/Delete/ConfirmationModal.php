<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Quiz\Delete\ConfirmationModal.
 */

namespace Kanooh\Paddle\Pages\Element\Quiz\Delete;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * The delete modal, used to confirm the deletion of an atom.
 *
 * @property ConfirmationForm $form
 *   Confirmation form inside the modal.
 */
class ConfirmationModal extends Modal
{
    /**
     * XPath selector of the form element.
     *
     * @var string
     */
    protected $formXPathSelector = '//form[@id="paddle-quiz-delete-form"]';

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
                return new ConfirmationForm($this->webdriver, $this->webdriver->byXPath($this->formXPathSelector));
        }
    }
}
