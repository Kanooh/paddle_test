<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\FormbuilderViewPage.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage;

/**
 * Administrative node view for a formbuilder node.
 *
 * @property FormbuilderViewPageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 */
class FormbuilderViewPage extends ViewPage
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new FormbuilderViewPageContextualToolbar($this->webdriver);
        }
        return parent::__get($property);
    }

    /**
     * Check if the custom form field is present.
     *
     * @param string $field
     *   The name of the field.
     *
     * @return bool
     *   TRUE if the filter field is present, FALSE otherwise.
     */
    public function checkCustomFormFieldPresent($field)
    {
        $xpath = '//form[contains(@class, "webform-client-form")]//input[@type="text"]';
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
        return (bool) count($elements);
    }
}
