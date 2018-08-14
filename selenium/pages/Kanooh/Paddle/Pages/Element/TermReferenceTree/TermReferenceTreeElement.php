<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\TermReferenceTree\TermReferenceTreeElement.
 */

namespace Kanooh\Paddle\Pages\Element\TermReferenceTree;

class TermReferenceTreeElement
{
    /**
     * Webdriver element that represents the term in a term reference tree.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Expand/collapse button.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $button;

    /**
     * Enable/disable checkbox.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $checkbox;

    /**
     * Term ID.
     *
     * @var int
     */
    protected $tid;

    /**
     * Term name.
     *
     * @var string
     */
    protected $name;

    /**
     * Constructor.
     *
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   Webdriver element (li) that represents the term.
     */
    public function __construct($element)
    {
        $this->element = $element;
        $this->initialize();
    }

    /**
     * Initializes all properties.
     */
    public function initialize()
    {
        try {
            $button_xpath = './div[contains(concat(" ", normalize-space(@class), " "), " term-reference-tree-button ")]';
            $this->button = $this->element->element($this->element->using('xpath')->value($button_xpath));
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            $this->button = null;
        }

        $checkbox_xpath = './div[contains(@class, "form-type-checkbox")]/input[@type="checkbox"]';
        $this->checkbox = $this->element->element($this->element->using('xpath')->value($checkbox_xpath));

        $this->tid = $this->checkbox->attribute('value');

        $name_xpath = './div[contains(@class, "form-type-checkbox")]/label';
        $name_element = $this->element->element($this->element->using('xpath')->value($name_xpath));
        $this->name = $name_element->text();
    }

    /**
     * Checks whether the term has any children in the reference tree.
     *
     * @return bool
     */
    public function hasChildren()
    {
        return !empty($this->button);
    }

    /**
     * Checks whether the children (if any) are visible.
     *
     * @return bool
     */
    public function hasVisibleChildren()
    {
        $button_class = $this->hasChildren() ? $this->button->attribute('class') : 'collapsed';
        return (strpos($button_class, 'collapsed') === false);
    }

    /**
     * Checks whether the term is selected.
     *
     * @return bool
     */
    public function selected()
    {
        return $this->getCheckbox()->selected();
    }

    /**
     * Selects the term if it isn't yet.
     */
    public function select()
    {
        if (!$this->selected()) {
            $this->getCheckbox()->click();
        }
    }

    /**
     * Deselects the term if it's selected.
     */
    public function deselect()
    {
        if ($this->selected()) {
            $this->getCheckbox()->click();
        }
    }

    /**
     * Returns the webdriver element.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * Returns the expand/collapse button. Returns null if no button is present.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function getExpandButton()
    {
        return $this->button;
    }

    /**
     * Returns the checkbox.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function getCheckbox()
    {
        return $this->checkbox;
    }

    /**
     * Returns the TID.
     *
     * @return int
     */
    public function getTid()
    {
        return $this->tid;
    }

    /**
     * Returns the term's name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
