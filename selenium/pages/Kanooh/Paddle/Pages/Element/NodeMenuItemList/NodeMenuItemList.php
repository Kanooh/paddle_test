<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\NodeMenuItemList\NodeMenuItemList.
 */

namespace Kanooh\Paddle\Pages\Element\NodeMenuItemList;

use Kanooh\Paddle\Pages\Element\Element;

class NodeMenuItemList extends Element
{
    protected $xpathSelector = '//div[contains(@class, "pane-node-menu-items")]';

    /**
     * Returns a list of associative arrays, each representing a menu item.
     *
     * Each menu item contains the following info:
     *  - 'path'
     *  - 'breadcrumb'
     *  - 'menu'
     *
     * @return NodeMenuItem[]
     */
    public function getMenuItems()
    {
        $items = array();

        $xpath = $this->xpathSelector . '//ul[contains(@class, "node-menu-links")]/li';

        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element[] $item_elements */
        /* @var $item_elements \PHPUnit_Extensions_Selenium2TestCase_Element[] */
        $item_elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));

        foreach ($item_elements as $item_element) {
            // Retrieve the mlid from the class attribute. It is in the format
            // 'mlid-123'.
            $mlid = array_reduce(
                explode(' ', $item_element->attribute('class')),
                function ($carry, $item) {
                    return strpos($item, 'mlid-') === 0 ? substr($item, 5) : $carry;
                }
            );

            $items[$mlid] = new NodeMenuItem($this->webdriver, $mlid);
        }

        return $items;
    }
}
