<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Scald\AddAssetModal.
 */

namespace Kanooh\Paddle\Pages\Element\Scald;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * The modal that allows to create an Scald atom.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $fileLink
 *   Link to add a file atom.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $imageLink
 *   Link to add an image atom.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $videoLink
 *   Link to add a video atom.
 */
class AddAssetModal extends Modal
{
    /**
     * {@inheritdoc}
     */
    public function waitUntilOpened()
    {
        // By default we wait for the submit button, but this modal doesn't have
        // any submit button. Instead wait for the list of items (links).
        $this->webdriver->waitUntilElementIsDisplayed($this->xpathSelector . '//div[contains(@class, "item-list")]');

        $this->getUniqueIds();
    }

    public function __get($name)
    {
        // Remove 'Link' from the end of the string.
        $name = preg_replace('/Link$/', '', $name);

        // Look for the corresponding link in the modal.
        $xpath = '//div[contains(@class, "modal-content")]//li[contains(@class, "' . $name . '")]/a';

        return $this->webdriver->byXPath($xpath);
    }
}
