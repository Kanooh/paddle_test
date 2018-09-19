<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PaneCollection\PaneCollectionTableRow.
 */

namespace Kanooh\Paddle\Pages\Element\PaneCollection;

use Kanooh\Paddle\Pages\Element\ElementNotPresentException;
use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class PaneCollectionTableRow
 *
 * @property string $title
 * @property PaneCollectionTableRowLinks $actions
 */
class PaneCollectionTableRow extends Row
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
                $cell = $this->element->byXPath('.//td[contains(@class, "views-field-title")]');
                return $cell->text();
                break;
            case 'actions':
                return new PaneCollectionTableRowLinks($this->webdriver, $this->element);
                break;
        }
        throw new ElementNotPresentException($name);
    }
}
