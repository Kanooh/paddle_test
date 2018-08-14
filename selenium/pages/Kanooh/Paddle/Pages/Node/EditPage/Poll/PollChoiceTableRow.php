<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\Poll\PollChoiceTableRow.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\Poll;

use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class PollChoiceTableRow
 *
 * @property Text $text
 *   The choice text field.
 */
class PollChoiceTableRow extends Row
{
    /**
     * The webdriver element of the Poll choice table row.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Construct a new PollChoiceTableRow.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The webdriver element of the table row.
     */
    public function __construct(WebDriverTestCase $webdriver, $element)
    {
        parent::__construct($webdriver);
        $this->element = $element;
    }

    /**
     * Magic getter for the element's properties.
     *
     * @param string $name
     *   The name of the property we need.
     *
     * @return mixed
     *   The element found.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'text':
                $field = $this->element->byXPath('./td/div[contains(@class, "form-type-textfield")]/input');
                return new Text($this->webdriver, $field);
                break;
        }
        throw new \RuntimeException("The property with the name $name is not defined.");
    }
}
