<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\WhoIsWho\WhoIsWhoPanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\WhoIsWho;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\PanelsContentType\SectionedPanelsContentType;
use Kanooh\Paddle\Pages\Element\PanelsContentType\WhoIsWho\ConfigurationForm;

/**
 * The 'WhoIsWho' Panels content type.
 */
class WhoIsWhoPanelsContentType extends SectionedPanelsContentType
{
    /**
     * {@inheritdoc}
     */
    const TYPE = 'who_is_who';

    /**
     * {@inheritdoc}
     */
    const TITLE = 'Who is who';

    /**
     * {@inheritdoc}
     */
    const DESCRIPTION = 'Renders the who is who.';

    /**
     * XPath selector of the form element.
     */
    public $formElementXPathSelector = '//form[@id="paddle-who-is-who-who-is-who-content-type-edit-form"]';

    /**
     * {@inheritdoc}
     */
    public function fillInConfigurationForm(Element $element = null)
    {
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
