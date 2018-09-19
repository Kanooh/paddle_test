<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Breadcrumb\BreadcrumbLink.
 */

namespace Kanooh\Paddle\Pages\Element\Breadcrumb;

use Kanooh\Paddle\Pages\Element\Element;

class BreadcrumbLink extends Element
{
    /**
     * Webdriver element that contains the link.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $container;

    /**
     * Webdriver element that's the actual breadcrumb segment.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Path of the link.
     *
     * @var string
     */
    protected $path;

    /**
     * Text of the link.
     *
     * @var string
     */
    protected $text;

    /**
     * CSS classes of both the container and the link.
     *
     * @var string[]
     */
    protected $classes;

    /**
     * Constructor.
     *
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $container
     *   Container element, in most cases a li.
     */
    public function __construct($container)
    {
        $this->container = $container;
        $this->initialize();
    }

    /**
     * Initializes all properties but the container.
     *
     * This is not directly in the constructor so we can re-initialize when / if
     * necessary.
     */
    public function initialize()
    {
        // Get the classes from the container.
        $this->classes = explode(' ', $this->container->attribute('class'));
        if (!in_array('breadcrumb-item-last', $this->classes)) {
            // Get the actual link.
            $link_criteria = $this->container->using('xpath')->value('./a');
            $this->element = $this->container->element($link_criteria);
            $this->path = $this->element->attribute('href');
        } else {
            // It is the last element - the title of the current page.
            $criteria = $this->container->using('xpath')->value('./span[contains(@class, "breadcrumb-title")]');
            $this->element = $this->container->element($criteria);
            $this->path = '';
        }
        $this->text = $this->element->text();

        // Get the classes from the link.
        $this->classes += explode(' ', $this->element->attribute('class'));
    }

    /**
     * Returns the container.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function getContainerElement()
    {
        return $this->container;
    }

    /**
     * Returns the actual link element.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function getLinkElement()
    {
        return $this->link;
    }

    /**
     * Returns the path of the link.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Returns the text of the link.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Returns the classes of the container and the link element.
     *
     * @return string[]
     */
    public function getClasses()
    {
        return $this->classes;
    }
}
