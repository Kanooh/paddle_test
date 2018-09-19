<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\OrganizationalUnitPanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\PanelsContentType\OrganizationalUnit\ConfigurationForm;

/**
 * The 'Organizational Unit' Panels content type.
 */
class OrganizationalUnitPanelsContentType extends SectionedPanelsContentType
{

    /**
     * {@inheritdoc}
     */
    const TYPE = 'organizational_unit';

    /**
     * {@inheritdoc}
     */
    const TITLE = 'Add organizational unit';

    /**
     * {@inheritdoc}
     */
    const DESCRIPTION = 'Add organizational unit.';

    /**
     * The node to show.
     *
     * @var string
     *   The node ID.
     */
    public $node;

    /**
     * The pane type which can be selected.
     *
     * @var string
     *   The pane type name.
     */
    public $view_mode;

    /**
     * XPath selector of the form element.
     */
    public $formElementXPathSelector = '//form[@id="paddle-organizational-unit-content-type-edit-form"]';

    /**
     * {@inheritdoc}
     */
    public function fillInConfigurationForm(Element $element = null)
    {
        /* @var ConfigurationForm $form */
        $form = $this->getForm($element);

        if (isset($this->node) && $this->node !== false) {
            $form->organizationalUnitAutocompleteField->fill($this->node);
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
