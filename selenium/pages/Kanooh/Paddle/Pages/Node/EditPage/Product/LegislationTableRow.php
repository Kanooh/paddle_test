<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\Product\LegislationTableRow.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\Product;

use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class LegislationTableRow
 *
 * @property Text $url
 * @property Text $title
 */
class LegislationTableRow extends Row
{
    /**
     * The webdriver element of the legislation table row.
     *
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
            case 'url':
                return new Text($this->webdriver, $this->element->byName('field_paddle_legislation[und][0][value]'));
            case 'title':
                return new Text($this->webdriver, $this->element->byName('field_paddle_legislation[und][0][title]'));
        }
        throw new \RuntimeException("The property with the name $name is not defined.");
    }
}
