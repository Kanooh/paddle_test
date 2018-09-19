<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Publication\AuthorsTableRow.
 */

namespace Kanooh\Paddle\Pages\Element\Publication;

use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class AuthorsTableRow
 *
 * @property AutoCompletedText $name
 */
class AuthorsTableRow extends Row
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
            case 'name':
                return new AutoCompletedText($this->webdriver, $this->element->byXPath('.//div[contains(@class, "form-type-textfield")]//input[@type="text"]'));
                break;
        }

        throw new \Exception("The property with the name $name is not defined.");
    }
}
