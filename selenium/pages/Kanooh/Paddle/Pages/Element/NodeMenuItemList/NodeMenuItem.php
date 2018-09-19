<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\NodeMenuItemList\NodeMenuItem.
 */

namespace Kanooh\Paddle\Pages\Element\NodeMenuItemList;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Represents a single item in the list of menu items on a node edit page.
 */
class NodeMenuItem extends Element
{
    /**
     * The 'View' icon.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public $viewIcon;

    /**
     * The 'Edit' icon.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public $editIcon;

    /**
     * The 'Delete' icon.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public $deleteIcon;

    /**
     * The mlid of the menu item.
     *
     * @var int
     */
    public $mlid;

    /**
     * The machine name of the menu to which the menu item belongs.
     *
     * @var string
     */
    public $menu;

    /**
     * The human readable name of the menu to which the menu item belongs.
     *
     * @var string
     */
    public $menuTitle;

    /**
     * The breadcrumb leading to the menu item.
     *
     * @var array
     */
    public $breadcrumb;

    /**
     * The webdriver element representing the node menu item.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Constructs a new NodeMenuItem object.
     *
     * @param WebdriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param string $mlid
     *   The mlid of the menu item.
     */
    public function __construct(WebDriverTestCase $webdriver, $mlid)
    {
        parent::__construct($webdriver);

        $this->xpathSelector = '//div[contains(@class, "pane-node-menu-items")]//li[contains(@class, "mlid-' . $mlid . '")]';
        $this->element = $this->getWebdriverElement();

        $this->viewIcon = $this->element->byXPath('./a[contains(@class, "ui-icon-visit")]');

        // Only add the edit icon if it is present. It might not be accessible
        // for editors.
        $elements = $this->element->elements($this->element->using('xpath')->value('./a[contains(@class, "ui-icon-edit")]'));
        if (!empty($elements)) {
            $this->editIcon = reset($elements);
        }

        // Only add the delete icon if it is present. It might not be accessible
        // for editors.
        $elements = $this->element->elements($this->element->using('xpath')->value('./a[contains(@class, "ui-icon-delete")]'));
        if (!empty($elements)) {
            $this->deleteIcon = reset($elements);
        }

        // Retrieve the menu machine name from the class attribute. It is in the
        // format 'menu-name-machine_name'.
        $this->menu = array_reduce(
            explode(' ', $this->element->byXPath('./span[contains(@class, "menu-name")]')->attribute('class')),
            function ($carry, $item) {
                return strpos($item, 'menu-name-') === 0 ? substr($item, 10) : $carry;
            }
        );
        $this->menuTitle = $this->element->byXPath('./span[contains(@class, "menu-name")]')->text();

        $this->breadcrumb = array();
        $breadcrumb_elements = $this->element->elements($this->element->using('xpath')->value('./span[contains(@class, "breadcrumb")]/span'));
        foreach ($breadcrumb_elements as $breadcrumb_element) {
            if ($breadcrumb_element->text() != t('(dominant breadcrumb)')) {
                $this->breadcrumb[] = $breadcrumb_element->text();
            }
        }

        $this->mlid = $mlid;
    }
}
