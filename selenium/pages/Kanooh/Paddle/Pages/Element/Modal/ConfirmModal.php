<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Modal\ConfirmModal.
 */

namespace Kanooh\Paddle\Pages\Element\Modal;

/**
 * Class for confirm modal dialogs.
 */
class ConfirmModal extends Modal
{
    /**
     * The XPath selector that identifies the confirm button.
     */
    protected $submitButtonXPathSelector = '//input[@type="submit" and @value="Confirm"]';

    /**
     * The XPath selector for the cancel submit button.
     */
    protected $cancelButtonXPathSelector = '//input[@type="submit" and @value="Cancel"]';

    /**
     * This is an alias for the submit() method.
     */
    public function confirm()
    {
        $this->submit();
    }

    /**
     * Clicks the cancel button on the confirmation modal.
     */
    public function cancel()
    {
        // Before submitting the modal, get its unique ids from the DOM so we
        // can use them to wait for the modal to close.
        $this->getUniqueIds();

        $cancel_button = $this->webdriver->byXPath($this->xpathSelector . $this->cancelButtonXPathSelector);
        $this->webdriver->moveto($cancel_button);
        $cancel_button->click();
        $this->waitUntilClosed();
    }
}
