<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\CustomCss\ContextTableRow.
 */

namespace Kanooh\Paddle\Pages\Element\CustomCss;

use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class ContextTableRow
 *
 * @property string $name
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkEdit
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkDelete
 */
class ContextTableRow extends Row
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
                $cell = $this->element->byXPath('.//td[contains(@class, "context-name")]');
                return $cell->text();
                break;
            case 'linkEdit':
                return $this->element->byXPath('.//td[contains(@class, "context-edit")]//a');
                break;
            case 'linkDelete':
                return $this->element->byXPath('.//td[contains(@class, "context-delete")]//a');
                break;
        }
    }
}
