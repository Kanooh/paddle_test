<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\SocialMedia\ShareWidget\ShareWidget.
 */

namespace Kanooh\Paddle\Pages\Element\SocialMedia\ShareWidget;

use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Represents the share widget pop-up.
 *
 * @property ShareWidgetDropdown $dropdown
 * @property ShareWidgetButton[] $shareButtons
 */
class ShareWidget
{
    /**
     * The Selenium webdriver.
     *
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * The Selenium webdriver element representing the share widget.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->webdriver = $webdriver;
        $this->element = $element;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'dropdown':
                $element = $this->element->byXPath('.//div[contains(@class, "compat-dropdown")]');
                return new ShareWidgetDropdown($this->webdriver, $element);
                break;
            case 'shareButtons':
                return $this->getShareButtons();
                break;
        }

        throw new \Exception("The property $name is undefined.");
    }

    /**
     * Retrieves all the visible share buttons.
     *
     * @return ShareWidgetButton[]
     *   The retrieved list of buttons.
     */
    protected function getShareButtons()
    {
        $criteria = $this->element->using('xpath')->value('./div[contains(@class, "addthis_toolbox")]/a');
        $elements = $this->element->elements($criteria);

        $buttons = array();
        foreach ($elements as $element) {
            $button = new ShareWidgetButton($this->webdriver, $element);
            $buttons[$button->name] = $button;
        }

        return $buttons;
    }
}
