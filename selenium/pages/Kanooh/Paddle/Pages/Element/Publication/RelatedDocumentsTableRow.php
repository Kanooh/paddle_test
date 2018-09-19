<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Publication\RelatedDocumentsTableRow.
 */

namespace Kanooh\Paddle\Pages\Element\Publication;

use Kanooh\Paddle\Pages\Element\Scald\AtomField;
use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class RelatedDocumentsTableRow
 *
 * @property AtomField $atom
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $removeButton
 */
class RelatedDocumentsTableRow extends Row
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
            case 'atom':
                $element = $this->element->byClassName('paddle-scald-atom-field');
                return new AtomField($this->webdriver, $element);
                break;
            case 'removeButton':
                return $this->element->byXPath('.//a[contains(@class, "ui-icon-delete")]');
                break;
        }
        throw new \Exception("The property with the name $name is not defined.");
    }
}
