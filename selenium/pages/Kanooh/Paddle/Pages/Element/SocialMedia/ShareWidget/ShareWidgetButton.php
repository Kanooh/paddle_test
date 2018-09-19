<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\SocialMedia\ShareWidget\ShareWidgetButton.
 */

namespace Kanooh\Paddle\Pages\Element\SocialMedia\ShareWidget;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class representing a share widget button link.
 *
 * @property string $name
 * @property string $text
 */
class ShareWidgetButton extends Element
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
            case 'name':
                $classes = $this->element->attribute('class');
                preg_match('/\baddthis_button_([^ ]+)\b/i', $classes, $matches);

                if (!empty($matches[1])) {
                    return $matches[1];
                }
                break;
            case 'text':
                return $this->element->text();
                break;
        }

        throw new \Exception("The property $name is undefined.");
    }

    /**
     * {@inheritdoc}
     */
    public function getWebdriverElement()
    {
        return $this->element;
    }
}
