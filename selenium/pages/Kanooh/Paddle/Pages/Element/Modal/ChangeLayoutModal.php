<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Modal\ChangeLayoutModal.
 */

namespace Kanooh\Paddle\Pages\Element\Modal;

use Kanooh\Paddle\Pages\Element\Form\Select;

/**
 * The modal that allows to change the layout of an entity.
 *
 * @property Select $selectLayoutCategory
 *   The list of layout categories available to select.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $confirmButton
 *   Confirm (delete) button.
 */
class ChangeLayoutModal extends Modal
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'selectLayoutCategory':
                return new Select($this->webdriver, $this->webdriver->byName('categories'));
            case 'confirmButton':
                $xpath = $this->getXPathSelector() . '//input[@value="Save"]';
                return $this->webdriver->byXPath($xpath);
        }

        throw new ModalFormElementNotDefinedException($name);
    }

    /**
     * {@inheritdoc}
     */
    public function waitUntilOpened()
    {
        $this->webdriver->waitUntilElementIsDisplayed('//div[@id="panels-layout-category-Paddle-Layouts-wrapper"]');

        // Store the modal's unique ids for later use.
        $this->getUniqueIds();
    }
}
