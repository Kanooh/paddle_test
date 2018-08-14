<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Scald\MovieYoutube\AddModal.
 */

namespace Kanooh\Paddle\Pages\Element\Scald\MovieYoutube;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Modal to add a new Youtube video.
 *
 * @property AddForm $form
 */
class AddModal extends Modal
{
    protected $formXpath = '//form[@id="scald-atom-add-form-add"]';

    /**
     * Makes the browser wait until the modal is fully loaded.
     *
     * This is determined by the fact that the form is displayed.
     */
    public function waitUntilOpened()
    {
        $this->webdriver->waitUntilElementIsDisplayed($this->formXpath);

        // Store the modal's unique ids for later use.
        $this->getUniqueIds();
    }

    public function __get($name)
    {
        switch ($name) {
            case 'form':
                return new AddForm($this->webdriver, $this->webdriver->byXPath($this->formXpath));
        }
    }
}
