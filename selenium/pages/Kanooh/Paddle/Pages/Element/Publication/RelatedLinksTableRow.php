<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Publication\RelatedLinksTableRow.
 */

namespace Kanooh\Paddle\Pages\Element\Publication;

use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class RelatedLinksTableRow
 *
 * @property Text $title
 * @property Text $url
 * @property Checkbox $newWindow
 */
class RelatedLinksTableRow extends Row
{
    /**
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver, $element)
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
            case 'title':
                return new Text($this->webdriver, $this->element->byXPath('.//div[contains(@class, "link-field-title")]//input'));
                break;
            case 'url':
                return new Text($this->webdriver, $this->element->byXPath('.//div[contains(@class, "link-field-url")]//input'));
                break;
            case 'newWindow':
                return new Checkbox($this->webdriver, $this->element->byXPath('.//div[@class="link-attributes"]//input'));
                break;
        }
        throw new \Exception("The property with the name $name is not defined.");
    }
}
