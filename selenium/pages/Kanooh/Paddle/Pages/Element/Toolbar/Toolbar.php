<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Toolbar\Toolbar.
 */

namespace Kanooh\Paddle\Pages\Element\Toolbar;

use Kanooh\Paddle\Pages\Element\Element;

/**
 * Base class for toolbars.
 */
abstract class Toolbar extends Element
{

    /**
     * The XPath selector that identifies the bar.
     *
     * @var string
     */
    protected $xpathSelector;

    /**
     * The XPath selector that identifies the link list.
     *
     * @var string
     */
    protected $xpathSelectorLinkList;

    /**
     * Magically provides all known buttons as properties.
     *
     * Properties that start with 'button' or 'dropdownButton', followed by the
     * machine name of a button defined in this::buttonInfo() can be used to
     * access the buttons directly. For example: $this->buttonPublish.
     *
     * @param string $name
     *   A button machine name prepending with 'button'.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     *   The matching button.
     */
    public function __get($name)
    {
        // If the property starts with 'button...' or 'dropdownButton' then
        // return the matching toolbar button.
        if (strpos($name, 'button') === 0) {
            $button_name = substr($name, 6);
            return $this->button($button_name);
        } elseif (strpos($name, 'dropdownButton') === 0) {
            $button_name = substr($name, 14);
            return $this->dropdownButton($button_name);
        }


        trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name, E_USER_NOTICE);
    }

    /**
     * Returns a button from the toolbar.
     *
     * This assumes that each button has a unique title.
     *
     * @param $name
     *   The machine name of the toolbar button, as defined in
     *   this::buttonInfo().
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected function button($name)
    {
        $conditions = $this->getButtonInfo($name);

        $xpath = $this->buttonXpath($conditions);

        return $this->getButtonByXpath($xpath, $name);
    }

    /**
     * Returns a button from the toolbar by xpath.
     *
     * @param $xpath
     *   The xpath to find a button in the contextual toolbar.
     * @param $name
     *   The machine name of the toolbar button, as defined in
     *   this::buttonInfo().
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     *
     * @throws ToolbarButtonNotPresentException
     *   Thrown when the requested button is not present.
     */
    protected function getButtonByXpath($xpath, $name)
    {
        try {
            return $this->webdriver->element($this->webdriver->using('xpath')->value($xpath));
        } catch (\Exception $ex) {
            throw new ToolbarButtonNotPresentException($name);
        }
    }

    /**
     * Returns a dropdown button from the contextual toolbar.
     *
     * This assumes that each button has a unique title.
     *
     * @param $name
     *   The machine name of the contextual toolbar dropdown button, as defined
     *   in this::buttonInfo().
     *
     * @return DropdownButton
     */
    protected function dropdownButton($name)
    {
        $conditions = $this->getButtonInfo($name);

        $xpath = $this->buttonXpath($conditions) . '/..';

        return new DropdownButton($this->webdriver, $xpath);
    }

    /**
     * Builds an xpath to find a (drop down) link in the contextual toolbar.
     *
     * @param  array $conditions
     *   The parts to build the xpath. Specify at least one of these:
     *   - classes: an array of CSS class(es) on the containing li tag.
     *   - href: href of the a tag.
     *   - title: link text. To be found within span tags, within the a tag.
     * @return string
     *   Xpath leading to the a tag.
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
        $attributes = array();
        if (!empty($conditions['href'])) {
            $attributes[] = '@href="' . $conditions['href'] . '"';
        }
        if (isset($conditions['data-paddle-contextual-toolbar-click'])) {
            $attributes[] = '@data-paddle-contextual-toolbar-click="' . $conditions['data-paddle-contextual-toolbar-click'] . '"';
        }
        if (count($attributes)) {
            $parts[] = '[' . implode(' and ', $attributes) . ']';
        }

        if (isset($conditions['title'])) {
            $parts[] = '//span';
            $parts[] = '[text()="' . $conditions['title'] . '"]';
            // 1 element back up, from the span tag to the a tag.
            $parts[] = '/..';
        }

        $xpath = implode('', $parts);

        return $xpath;
    }

    /**
     * Returns metadata about a given toolbar button.
     *
     * @param string $name
     *   The machine name of the button.
     *
     * @return array
     *   An associative array of button metadata with the following keys:
     *   - classes: an indexed array of classes that are present on the button.
     *   - href: the URL the button leads to, or '#' if the button does not link
     *     to another page.
     *
     * @throws ToolBarButtonNotDefinedException
     *   Throws an exception if no button is defined with the given name.
     */
    public function getButtonInfo($name)
    {
        $button_info = $this->buttonInfo();
        if (empty($button_info[$name])) {
            throw new ToolbarButtonNotDefinedException($name);
        }
        return $button_info[$name];
    }

    /**
     * Returns metadata about all available toolbar buttons on this page.
     *
     * @todo Add multilingual support.
     *
     * @return array
     *   An associative array of button metadata, keyed by button name, each
     *   value constisting of an associative array with the following keys:
     *   - classes: an indexed array of classes that are present on the button.
     *   - href: the URL the button leads to, or '#' if the button does not link
     *     to another page.
     *   - title: the title of the button.
     */
    abstract public function buttonInfo();

    /**
     * Checks the presence of an array of buttons on a page.
     *
     * @param array $names
     *   Array containing the machine names of the expected buttons.
     *   If omitted, will check for the presence of all defined buttons.
     */
    public function checkButtons(array $names = array())
    {
        // Default to all available buttons.
        $names = $names ?: array_keys($this->buttonInfo());

        foreach ($names as $name) {
            // Access the button. If the button is not present this will throw
            // an exception.
            $this->button($name);
        }
    }

    /**
     * Checks that the given buttons are not available on the page.
     *
     * @param array $names
     *   Array containing the machine names of the buttons that are expected to
     *   be absent.
     */
    public function checkButtonsNotPresent(array $names)
    {
        foreach ($names as $name) {
            // Access the button. If the button is not present this will throw
            // an exception, which is what we expect to happen, so catch it and
            // carry on. If the button is present we throw an exception of our
            // own.
            try {
                $this->button($name);
                throw new ToolbarButtonPresentException($name);
            } catch (ToolbarButtonNotPresentException $e) {
            }
        }
    }
}
