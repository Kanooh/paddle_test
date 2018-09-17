<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\DownloadListPanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\PanelsContentType\UiTDatabank\ConfigurationForm;

/**
 * The 'UiTDatabank' Panels content type.
 */
class UiTDatabankPanelsContentType extends SectionedPanelsContentType
{
    /**
     * {@inheritdoc}
     */
    const TYPE = 'uitdatabank_pane';

    /**
     * {@inheritdoc}
     */
    const TITLE = 'UiTdatabank Pane';

    /**
     * {@inheritdoc}
     */
    const DESCRIPTION = 'Add a pane which corresponds with the UiTdatabank.';

    /**
     * The event to show.
     *
     * @var string
     *   The event title.
     */
    public $event;

    /**
     * The pane type which can be selected.
     *
     * @var string
     *   The pane type name.
     */
    public $selection_type;

    /**
     * XPath selector of the form element.
     */
    public $formElementXPathSelector = '//form[@id="paddle-cultuurnet-uitdatabank-pane-content-type-edit-form"]';

    /**
     * {@inheritdoc}
     */
    public function fillInConfigurationForm(Element $element = null)
    {
        /* @var ConfigurationForm $form */
        $form = $this->getForm($element);

        if (isset($this->selection_type)) {
            $form->selectionType->select($this->selection_type);

            if ($this->selection_type == "spotlight" && isset($this->event) && $this->event !== false) {
                $form->spotlightEvent->fill($this->event);
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
