<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\PaneToolbar.
 */

namespace Kanooh\Paddle\Pages\Element\Pane;

use Kanooh\Paddle\Pages\Element\Toolbar\Toolbar;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The contextual toolbar for the generic administrative node view.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonEdit
 *   The "Edit" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonDelete
 *   The "Delete" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonPaddleStyle
 *   The "Style" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonDragHandle
 *   The drag handle.
 */
class PaneToolbar extends Toolbar
{

    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//div[@class = "panels-ipe-dragbar"]';

    /**
     * {@inheritdoc}
     */
    protected $xpathSelectorLinkList = '//ul[@class = "panels-ipe-linkbar"]';

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver, $xpath_selector_pane)
    {
        parent::__construct($webdriver);
        $this->xpathSelector = $xpath_selector_pane;
    }

    /**
     * {@inheritdoc}
     */
    public function buttonInfo()
    {
        // Defines all the possible toolbar buttons.
        $buttons = array(
            'Edit' => array(
                'title' => 'Settings',
                'classes' => array('edit'),
            ),
            'Delete' => array(
                'title' => 'Delete',
                'classes' => array('delete'),
            ),
            'PaddleStyle' => array(
                'title' => 'Style',
                'classes' => array('paddle_style'),
            ),
            'DragHandle' => array(
                'classes' => array('panels-ipe-draghandle-icon-inner'),
            ),
        );

        return $buttons;
    }

    /**
     * {@inheritdoc}
     */
    protected function button($name)
    {
        if ($name == 'DragHandle') {
            // Don't rely on the parent to get the DragHandle because the
            // DragHandle isn't rendered within the usual HTML structure.
            $conditions = $this->getButtonInfo($name);

            $parts[] = $this->xpathSelector;
            if (!empty($conditions['classes'])) {
                $contains = array();
                foreach ($conditions['classes'] as $class) {
                    $contains[] = 'contains(@class,"' . $class . '")';
                }
                $parts[] = '//span[' . implode(' and ', $contains) . ']';
            }
            $xpath = implode('', $parts);

            return $this->getButtonByXpath($xpath, $name);
        }
        return parent::button($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function buttonXpath($conditions)
    {
        $conditions += array(
            'classes' => array(),
            'href' => null,
            'title' => null,
        );

        $parts[] = $this->xpathSelector;
        $parts[] = $this->xpathSelectorLinkList;

        // Add the classes for the wrapping <li>.
        if (!empty($conditions['classes'])) {
            $contains = array();
            foreach ($conditions['classes'] as $class) {
                $contains[] = 'contains(@class,"' . $class . '")';
            }
            $parts[] = '//li[' . implode(' and ', $contains) . ']';
        }

        $parts[] = '//a';
        if (isset($conditions['href']) && $conditions['href'] == '#') {
            $attributes = array();

            $attributes[] = '@href="#"';

            if (isset($conditions['title'])) {
                $attributes[] = 'text()="' . $conditions['title'] . '"';
            }

            $parts[] = '[' . implode(' and ', $attributes) . ']';
        } elseif (isset($conditions['data-paddle-contextual-toolbar-click'])) {
            $parts[] = '[@data-paddle-contextual-toolbar-click="' . $conditions['data-paddle-contextual-toolbar-click'] . '"]';
        } else {
            if (!empty($conditions['href'])) {
                $parts[] = '[@href="' . $conditions['href'] . '"]';
            }

            $parts[] = '//span';

            if (!empty($conditions['title'])) {
                $parts[] = '[text()="' . $conditions['title'] . '"]';
            }
            $parts[] = '/..';
        }

        $xpath = implode('', $parts);

        return $xpath;
    }
}
