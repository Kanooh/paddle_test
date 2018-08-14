<?php
/**
 * @file
 */

namespace Kanooh\Paddle\Pages\Element\Scald;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Class AddAtomModal
 * @package Kanooh\Paddle\Pages\Element\Scald
 *
 * @property AddAtomForm $form
 */
class AddAtomModal extends Modal
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

        // Store the unique ids of the modal for later use.
        $this->getUniqueIds();
    }

    public function __get($name)
    {
        switch ($name) {
            case 'form':
                return new AddAtomForm($this->webdriver, $this->webdriver->byXPath($this->formXpath));
        }

        throw new \Exception("The property $name is undefined.");
    }
}
