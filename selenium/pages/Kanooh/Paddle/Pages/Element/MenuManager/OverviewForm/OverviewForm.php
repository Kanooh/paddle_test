<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\MenuManager\OverviewForm\OverviewForm.
 */

namespace Kanooh\Paddle\Pages\Element\MenuManager\OverviewForm;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;

/**
 * Class representing the Menu overview form.
 *
 * @property OverviewFormTable $overviewFormTable
 *   The table containing the menu items.
 * @property PHPUnit_Extensions_Selenium2TestCase_Element $showWeightsToggleLink
 *   The link to toggle the weight control.
 *
 * @todo - add a method to find all the parents of a menu item to avoid having
 *   to pass them to openTreeToMenuItem().
 */
class OverviewForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'overviewFormTable':
                return new OverviewFormTable($this->webdriver);
            case 'showWeightsToggleLink':
                return $this->element->byXpath('.//a[contains(@class, "tabledrag-toggle-weight")]');
        }
        throw new FormFieldNotDefinedException($name);
    }

    /**
     * Opens the menu tree to reach a specific menu item.
     *
     * @param  array $parents
     *   The mlids of the parents of the menu item we are looking for.
     * @param  string $menu_item_title
     *   The menu title of the searched menu item.
     *
     */
    public function openTreeToMenuItem($parents, $menu_item_title)
    {
        foreach ($parents as $parent) {
            // Get the parent row.
            $parent_item_row = $this->overviewFormTable->getMenuItemRowByMlid($parent);
            if (!$parent_item_row->childItemsShown()) {
                // Open the link.
                $parent_item_row->linkShowChildItems->click();
                $parent_item_row->waitUntilChildItemsArePresent();
            }
        }

        $this->webdriver->waitUntilTextIsPresent($menu_item_title);
    }

    /**
     * Find the language of the menu displayed in the form.
     *
     * @return string
     *   The language of the menu displayed in the form.
     */
    public function getMenuLanguage()
    {
        return $this->element->attribute('data-menu-language');
    }
}
