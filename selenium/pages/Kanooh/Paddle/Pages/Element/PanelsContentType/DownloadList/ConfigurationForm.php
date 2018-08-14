<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\DownloadList\ConfigurationForm.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\DownloadList;

use Kanooh\Paddle\Pages\Element\Form\SelectionTypeRadioButtons;
use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Utilities\AjaxService;

/**
 * Configuration form for the download list panels content type.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $addButton
 *   The button to add a new row in the downloads table.
 * @property DownloadsTable $downloadsTable
 *   The list of selected atoms when manual selection type is active.
 * @property AutoCompletedText $filterGeneralTags
 *   The autocomplete text field to filter by general tags.
 * @property AutoCompletedText $filterTags
 *   The autocomplete text field to filter by tags.
 * @property SelectionTypeRadioButtons $selectionType
 *   The radio buttons to choose the atoms selection type.
 * @property RadioButton $sortAlphabeticalAsc
 *   Radio button to sort atoms by alphabetical ascending.
 * @property RadioButton $sortAlphabeticalDesc
 *   Radio button to sort atoms by alphabetical descending.
 * @property RadioButton $sortFilesizeAsc
 *   Radio button to sort atoms by file size ascending.
 * @property RadioButton $sortFilesizeDesc
 *   Radio button to sort atoms by file size descending.
 */
class ConfigurationForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'addButton':
                $xpath = './/input[@value="Add row"]';
                return $this->element->byXPath($xpath);
            case 'downloadsTable':
                return new DownloadsTable(
                    $this->webdriver,
                    $this->element->byXPath('.//table[contains(@id, "paddle-scald-draggable-atoms")]')
                );
            case 'filterGeneralTags':
                return new AutoCompletedText(
                    $this->webdriver,
                    $this->webdriver->byXPath('//input[@name="terms[paddle_general]"]')
                );
            case 'filterTags':
                return new AutoCompletedText(
                    $this->webdriver,
                    $this->webdriver->byXPath('//input[@name="terms[paddle_tags]"]')
                );
            case 'selectionType':
                return new SelectionTypeRadioButtons(
                    $this->webdriver,
                    $this->element->byClassName('form-item-selection-type')
                );
            case 'sortAlphabeticalAsc':
                return new RadioButton(
                    $this->webdriver,
                    $this->element->byXPath('.//input[@value="title_asc"]')
                );
            case 'sortAlphabeticalDesc':
                return new RadioButton(
                    $this->webdriver,
                    $this->element->byXPath('.//input[@value="title_desc"]')
                );
            case 'sortFilesizeAsc':
                return new RadioButton(
                    $this->webdriver,
                    $this->element->byXPath('.//input[@value="filesize_asc"]')
                );
            case 'sortFilesizeDesc':
                return new RadioButton(
                    $this->webdriver,
                    $this->element->byXPath('.//input[@value="filesize_desc"]')
                );
        }

        throw new FormFieldNotDefinedException($name);
    }

    /**
     * Helper method to add a new row to the downloads table.
     */
    public function addRow()
    {
        $ajax_service = new AjaxService($this->webdriver);
        $ajax_service->markAsWaitingForAjaxCallback($this->addButton);
        $this->addButton->click();
        $ajax_service->waitForAjaxCallback($this->addButton);
    }
}
