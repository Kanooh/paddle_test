<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Modal\EditPaneModal.
 */

namespace Kanooh\Paddle\Pages\Element\Modal;

/**
 * The modal that allows to edit a pane on a Panels display editor page.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkUrl
 */
class EditPaneModal extends Modal
{
    /**
     * {@inheritdoc}
     */
    protected $submitButtonXPathSelector = '//input[@id="edit-return"]';

    /**
     * Returns all fieldsets in the root level of the edit pane settings div.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element[]
     *   List of fieldset elements, sorted by their order in the DOM.
     */
    public function getSectionFieldsets()
    {
        $xpath = './/div[@id="edit-pane-settings"]/fieldset';
        $modal = $this->getWebdriverElement();
        $criteria = $modal->using('xpath')->value($xpath);
        return $modal->elements($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'linkUrl':
                $xpath =  '//a[contains(@class, "panel-link")]';
                return $this->getWebdriverElement()->byXPath($xpath);
        }

        trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name, E_USER_NOTICE);
        return false;
    }
}
