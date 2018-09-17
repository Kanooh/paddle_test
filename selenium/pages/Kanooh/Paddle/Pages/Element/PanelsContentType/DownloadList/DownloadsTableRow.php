<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\DownloadList\DownloadsTableRow.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\DownloadList;

use Kanooh\Paddle\Pages\Element\Scald\AtomField;
use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class representing a row in the download list pane table.
 *
 * @package Kanooh\Paddle\Pages\Element\PanelsContentType\DownloadList
 *
 * @property AtomField $atom
 *   The atom field element.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $removeButton
 *   The button to remove the current row.
 */
class DownloadsTableRow extends Row
{
    /**
     * The Webdriver element for the table row instance.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * @inheritDoc
     */
    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        parent::__construct($webdriver);

        $this->element = $element;
    }

    /**
     * @inheritDoc
     */
    public function __get($name)
    {
        switch ($name) {
            case 'atom':
                $element = $this->element->byClassName('paddle-scald-atom-field');
                return new AtomField($this->webdriver, $element);
            case 'removeButton':
                return $this->element->byXPath('.//input[contains(@name, "remove_atom_")]');
        }

        throw new \Exception("The property $name is undefined.");
    }

    /**
     * Removes this row from the table.
     */
    public function remove()
    {
        // Click the remove button and wait for the element to become stale.
        $element = $this->element;
        $this->removeButton->click();
        $this->webdriver->waitUntil(
            function () use ($element) {
                try {
                    /* @var \PHPUnit_Extensions_Selenium2TestCase_Element $element */
                    $element->displayed();
                    return null;
                } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
                    // The element is stale.
                    return true;
                }
            },
            $this->webdriver->getTimeout()
        );
    }
}
