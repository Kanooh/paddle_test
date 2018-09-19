<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\SocialMedia\ShareWidget\ShareWidgetDropdown.
 */

namespace Kanooh\Paddle\Pages\Element\SocialMedia\ShareWidget;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class representing the dropdown in the share widget.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $toggle
 *   The button to toggle the display of this element.
 * @property ShareWidgetButton[] $shareButtons
 */
class ShareWidgetDropdown extends Element
{

    /**
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        parent::__construct($webdriver);
        $this->element = $element;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'toggle':
                return $this->element->byXPath('.//a[@class="compat-dropdown__toggle"]');
                break;
            case 'shareButtons':
                return $this->getShareButtons();
                break;
        }

        throw new \Exception("The property $name is undefined.");
    }

    /**
     * Retrieves the share buttons inside the dropdown.
     *
     * @return ShareWidgetButton[]
     *   The retrieved list of buttons.
     */
    protected function getShareButtons()
    {
        $criteria = $this->element->using('xpath')->value('./div[contains(@class, "compat-dropdown__list")]/a');
        $elements = $this->element->elements($criteria);

        $buttons = array();
        foreach ($elements as $element) {
            $button = new ShareWidgetButton($this->webdriver, $element);
            $buttons[$button->name] = $button;
        }

        return $buttons;
    }
}
