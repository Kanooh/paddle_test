<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Formbuilder\PermissionsTableRow.
 */

namespace Kanooh\Paddle\Pages\Element\Formbuilder;

use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class PermissionsTableRow
 *
 * @property string $permission
 */
class PermissionsTableRow extends Row
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
            case 'permission':
                $cell = $this->element->byXPath('.//td[contains(@class, "permission")]//div[contains(@class, "form-type-item")]');
                return $cell->text();
                break;
        }
    }

    /**
     * Gets a checkbox by giving a specific element name.
     *
     * @param string $name
     *   The name for the html element.
     *
     * @return Checkbox
     *   Returns the corresponding checkbox.
     */
    public function getCheckbox($name)
    {
        return new Checkbox($this->webdriver, $this->element->byName($name));
    }
}
