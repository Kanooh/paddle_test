<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Users\Roles\RoleManagementTableRow.
 */

namespace Kanooh\Paddle\Pages\Admin\Users\Roles;

use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class RoleManagementTableRow
 *
 * @property string $role_name
 * @property RoleManagementTableRowLinks $actions
 */
class RoleManagementTableRow extends Row
{
    /**
     * The webdriver element of the role management table row.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Construct a new RoleManagementTableRow.
     *
     * @param WebDriverTestCase $webdriver
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
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
            case 'role_name':
                $cell = $this->element->byXPath('.//td[contains(@class, "views-field-name")]');
                return $cell->text();
                break;
            case 'actions':
                return new RoleManagementTableRowLinks($this->webdriver, $this->element);
                break;
        }

        throw new \Exception("The property with the name $name is not defined.");
    }
}
