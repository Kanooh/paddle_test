<?php

/**
 * @file
 * Contains
 *   \Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage\MenuOverviewPageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;

/**
 * The contextual toolbar for the menu overview page.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonCreateMenu
 *   The "Create menu" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonCreateMenuItem
 *   The "Create menu item" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonEditMenu
 *   The "Edit menu" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSave
 *   The "Save" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonDeleteMenu
 *   The "Delete" button.
 */
class MenuOverviewPageContextualToolbar extends ContextualToolbar
{

    /**
     * {@inheritdoc}
     */
    public function buttonInfo()
    {
        // Defines all the possible contextual buttons.
        $buttons = array(
            'CreateMenu' => array(
                'title' => 'Create menu',
            ),
            'CreateMenuItem' => array(
                'title' => 'Create menu item',
            ),
            'EditMenu' => array(
                'title' => 'Edit menu',
            ),
            'Save' => array(
                'title' => 'Save',
            ),
            'DeleteMenu' => array(
                'title' => 'Delete menu',
            ),
        );

        return $buttons;
    }
}
