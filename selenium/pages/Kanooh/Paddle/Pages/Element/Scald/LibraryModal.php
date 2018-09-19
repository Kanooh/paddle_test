<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Scald\LibraryModal.
 */

namespace Kanooh\Paddle\Pages\Element\Scald;

use Kanooh\Paddle\Pages\Element\Modal\Modal;
use Kanooh\Paddle\Pages\Element\Modal\ModalFormElementNotDefinedException;

/**
 * The scald library modal which allows the user to pick an atom.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $addAssetButton
 * @property Library $library
 */
class LibraryModal extends Modal
{
    /**
     * {@inheritdoc}
     */
    protected $addAssetButtonXPathSelector = '//a[@id="add-asset-button"]';

    /**
     * Makes the browser wait until the modal is fully loaded.
     *
     * This is determined by the fact that the form is displayed.
     */
    public function waitUntilOpened()
    {
        $this->webdriver->waitUntilElementIsDisplayed('//form[contains(@id, "paddle-scald-library-form")]');

        // Store the modal's unique ids for later use.
        $this->getUniqueIds();
    }

    public function __get($name)
    {
        switch ($name) {
            case 'addAssetButton':
                return $this->webdriver->byXPath($this->addAssetButtonXPathSelector);
            case 'library':
                return new Library($this->webdriver, '//form[contains(@class, "paddle-scald-library-modal-form")]');
            default:
        }
        throw new ModalFormElementNotDefinedException($name);
    }
}
