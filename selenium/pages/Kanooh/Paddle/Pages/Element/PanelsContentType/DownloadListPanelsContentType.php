<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\DownloadListPanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\PanelsContentType\DownloadList\ConfigurationForm;
use Kanooh\Paddle\Pages\Element\Scald\LibraryModal;

/**
 * The 'DownloadList' Panels content type.
 */
class DownloadListPanelsContentType extends SectionedPanelsContentType
{
    /**
     * {@inheritdoc}
     */
    const TYPE = 'download_list';

    /**
     * {@inheritdoc}
     */
    const TITLE = 'Download list';

    /**
     * {@inheritdoc}
     */
    const DESCRIPTION = 'Add a download list.';

    /**
     * The downloadable atoms field value.
     *
     * @var int[]
     */
    public $atomIds;

    /**
     * XPath selector of the form element.
     */
    public $formElementXPathSelector = '//form[@id="paddle-scald-download-list-content-type-edit-form"]';

    /**
     * {@inheritdoc}
     */
    public function fillInConfigurationForm(Element $element = null)
    {
        /* @var ConfigurationForm $form */
        $form = $this->getForm($element);

        if ($this->atomIds && is_array($this->atomIds)) {
            $rows = $form->downloadsTable->rows;

            // Remove all existing files.
            foreach ($rows as $row) {
                $row->remove();
            }

            foreach ($this->atomIds as $index => $atom_id) {
                $form->addRow();
                $row = end($form->downloadsTable->rows);
                $this->webdriver->moveto($row->atom->selectButton);
                $row->atom->selectButton->click();

                $library_modal = new LibraryModal($this->webdriver);
                $library_modal->waitUntilOpened();
                $atom = $library_modal->library->getAtomById($atom_id);
                $atom->insertLink->click();
                $library_modal->waitUntilClosed();
            }
        }

        $this->fillInSections();
    }

    /**
     * {@inheritdoc}
     */
    public function getForm(Element $element = null)
    {
        $xpath_selector = !empty($element) ? $element->getXPathSelector() : '';

        $form_xpath = $xpath_selector . $this->formElementXPathSelector;

        // Wait until the form is fully loaded, otherwise the test might fail.
        $form_element = $this->webdriver->waitUntilElementIsDisplayed($form_xpath);

        return new ConfigurationForm($this->webdriver, $form_element);
    }
}
