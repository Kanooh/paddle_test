<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\MenuStructurePanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\Form\AjaxSelect;

/**
 * The 'Menu structure' Panels content type.
 *
 * @property AjaxSelect $menu
 *   The menu field.
 * @property AjaxSelect $menuItem
 *   The menu item field.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element_Select $level
 *   The level field.
 */
class MenuStructurePanelsContentType extends SectionedPanelsContentType
{

    /**
     * {@inheritdoc}
     */
    const TYPE = 'add_menu_structure';

    /**
     * {@inheritdoc}
     */
    const TITLE = 'Add new menu structure';

    /**
     * {@inheritdoc}
     */
    const DESCRIPTION = 'Add a new menu structure.';

    /**
     * The list type.
     *
     * @var string
     *   One of the following:
     *   - "bullet-list"
     *   - "regular-list""
     */
    public $listType;

    /**
     * {@inheritdoc}
     *
     * @todo Refactor to use the Form class.
     */
    public function fillInConfigurationForm(Element $element = null)
    {
        // @todo Fill in the configuration options.

        $this->fillInSections();
    }

    /**
     * {@inheritdoc}
     */
    public function getForm(Element $element = null)
    {
        // @todo Implement.
    }

    /**
     * Magic getter.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'menu':
                return new AjaxSelect($this->webdriver, $this->webdriver->byName('menus'));
            case 'menuItem':
                return new AjaxSelect($this->webdriver, $this->webdriver->byName('menu_items'));
            case 'level':
                return $this->webdriver->select($this->webdriver->byXPath('//select[@name="item_levels"]'));
        }
        return parent::__get($name);
    }
}
