<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Embed\WidgetTableRow.
 */

namespace Kanooh\Paddle\Pages\Element\Embed;

use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class WidgetTableRow
 *
 * @property string $title
 *   Title of the widget.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkEdit
 *   The widget's edit link.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkDelete
 *   The widget's delete link.
 * @property int $wid
 *   The Widget ID.
 */
class WidgetTableRow extends Row
{
    /**
     * The webdriver element of the widget table row.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Construct a new WidgetTableRow.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The webdriver element of the widget table row.
     */
    public function __construct(WebDriverTestCase $webdriver, $element)
    {
        parent::__construct($webdriver);
        $this->element = $element;
    }

    /**
     * Magic getter for the widget list item's properties.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'title':
                $cell = $this->element->byXPath('.//td[contains(@class, "widget-title")]');
                return $cell->text();
                break;
            case 'linkPreview':
                return $this->element->byXPath('.//td[contains(@class, "widget-preview")]//a');
                break;
            case 'linkEdit':
                return $this->element->byXPath('.//td[contains(@class, "widget-edit")]//a');
                break;
            case 'linkDelete':
                return $this->element->byXPath('.//td[contains(@class, "widget-delete")]//a');
                break;
            case 'wid':
                return $this->element->attribute('data-widget-id');
                break;
        }
    }
}
