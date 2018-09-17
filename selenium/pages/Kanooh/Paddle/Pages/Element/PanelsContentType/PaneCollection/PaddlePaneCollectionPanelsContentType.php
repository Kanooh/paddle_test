<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\PaneCollection\PaddlePaneCollectionPanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\PaneCollection;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\PanelsContentType\PanelsContentType;

/**
 * The 'Paddle Pane Collection' Panels content type.
 */
class PaddlePaneCollectionPanelsContentType extends PanelsContentType
{

    /**
     * {@inheritdoc}
     */
    const TYPE = 'paddle_pane_collection';

    /**
     * {@inheritdoc}
     */
    const TITLE = 'Pane Collection';

    /**
     * {@inheritdoc}
     */
    const DESCRIPTION = 'Add a pane collection.';

    /**
     * XPath selector of the form element.
     */
    public $formElementXPathSelector = '//form[@id="paddle-pane-collection-paddle-pane-collection-content-type-edit-form"]';

    /**
     * {@inheritdoc}
     */
    public function fillInConfigurationForm(Element $element = null)
    {
        // Just because we have to implement this method. But we don't make it
        // work because its use is deprecated anyway.
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
