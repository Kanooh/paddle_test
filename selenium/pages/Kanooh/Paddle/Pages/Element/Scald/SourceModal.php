<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Scald\SourceModal.
 */

namespace Kanooh\Paddle\Pages\Element\Scald;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * The modal to choose the source of the new atom.
 */
class SourceModal extends Modal
{
    /**
     * {@inheritdoc}
     */
    public function waitUntilOpened()
    {
        // By default we wait for the submit button, but this modal doesn't have
        // any submit button. Instead wait for the list of choices.
        $this->webdriver->waitUntilElementIsDisplayed($this->xpathSelector . '//div[contains(@id, "edit-source")]');
        $this->getUniqueIds();
    }

    /**
     * Chooses a specified source.
     *
     * @param string $source
     *   Machine name of the source.
     */
    public function chooseSource($source)
    {
        $xpath = '//input[@type="radio"][@name="source"][@value="' . $source . '"]';
        $radio = new RadioButton($this->webdriver, $this->webdriver->byXPath($xpath));
        $radio->select();
        $this->submit();
    }
}
