<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Cultuurnet\UiTDatabankEvent.
 */

namespace Kanooh\Paddle\Pages\Element\Cultuurnet;

use Kanooh\WebDriver\WebDriverTestCase;

/**
 * An UiTDatabank event displayed in the frontend pane.
 *
 * @property string $title
 * @property string $url
 * @property string $imageUrl
 * @property string $period
 */
class UiTDatabankEvent
{
    /**
     * The Selenium web driver element representing the UiTDatabank event.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * @var WebDriverTestCase
     */
    protected $webdriver;

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
            case 'imageUrl':
                $element = $this->element->byXPath('.//figure/img');
                return $element->attribute('src');
                break;
            case 'url':
                $element = $this->element->byXPath('.//a[contains(@class, "paddle-cultuurnet-event-link")]');
                return $element->attribute('href');
                break;
            case 'title':
                $element = $this->element->byXPath('.//div[contains(@class, "spotlight-bottom")]/h3');
                return $element->text();
                break;
            case 'period':
                $element = $this->element->byXPath('.//div[contains(@class, "spotlight-period")]');
                return $element->text();
                break;
        }
        throw new \Exception("The property with the name $name is not defined.");
    }
}
