<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Modal\AddPaneModal.
 */

namespace Kanooh\Paddle\Pages\Element\Modal;

use Kanooh\Paddle\Pages\Element\PanelsContentType\PanelsContentType;

/**
 * The modal that allows to add a pane to a region.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $addPaneList
 *   The list of pane types available to select.
 */
class AddPaneModal extends Modal
{

    /**
     * {@inheritdoc}
     */
    protected $submitButtonXPathSelector = '//input[@name="op"][@value="Finish"]';

    /**
     * @param string $property
     *   The property that was not returned by any of the child classes.
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     * @throws \Exception
     */
    public function __get($property)
    {
        switch ($property) {
            case 'addPaneList':
                $xpath = $this->xpathSelector . '//ul[contains(@class, "paddle-add-pane-list")]';
                $this->webdriver->waitUntilElementIsDisplayed($xpath);
                return $this->webdriver->byXPath($xpath);
        }

        throw new \Exception("The property $property is undefined.");
    }

    /**
     * {@inheritdoc}
     */
    public function waitUntilOpened()
    {
        // By default we wait for the submit button, but the submit button for
        // this pane only appears in the second step.
        $this->webdriver->waitUntilElementIsDisplayed($this->closeButtonXPathSelector);
    }

    /**
     * Selects the requested pane type in the left column.
     *
     * This will wait until the form is loaded before returning.
     *
     * @param PanelsContentType $content_type
     *   The Panels content type to select.
     */
    public function selectContentType(PanelsContentType $content_type)
    {
        $this->webdriver->clickOnceElementIsVisible($this->addPaneList->byXPath('//a/span/div[text()="' . $content_type::TITLE . '"]'));
        $content_type->waitUntilReady();
    }
}
