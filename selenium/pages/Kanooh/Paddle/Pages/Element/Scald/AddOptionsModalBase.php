<?php
/**
 * @file
 */

namespace Kanooh\Paddle\Pages\Element\Scald;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Class AddOptionsModalBase
 * @package Kanooh\Paddle\Pages\Element\Scald
 */
class AddOptionsModalBase extends Modal
{
    protected $formXpath = '//form[@data-form-id="scald_atom_add_form_options"]';

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
}
