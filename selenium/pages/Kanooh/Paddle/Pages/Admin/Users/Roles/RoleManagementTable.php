<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Users\Roles\RoleManagementTable.
 */

namespace Kanooh\Paddle\Pages\Admin\Users\Roles;

use Kanooh\Paddle\Pages\Element\Table\Table;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Table containing the Drupal user roles.
 *
 * @property RoleManagementTableRow[] $rows
 */
class RoleManagementTable extends Table
{
    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver, $xpath)
    {
        parent::__construct($webdriver);
        $this->xpathSelector = $xpath;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'rows':
                $rows = array();
                $criteria = $this->element->using('xpath')->value('.//tbody//tr');
                $elements = $this->element->elements($criteria);
                foreach ($elements as $element) {
                    $rows[] = new RoleManagementTableRow($this->webdriver, $element);
                }
                return $rows;
                break;
        }
        throw new \Exception("The property with the name $name is not defined.");
    }

    /**
     * Returns a table row based on the given role name.
     *
     * @param string $role_name
     *   The unique name of the role.
     *
     * @return RoleManagementTableRow
     *   The corresponding table row holding the row info when found, otherwise
     *   it will return null.
     */
    public function getRoleManagementTableRowByRoleName($role_name)
    {
        $row_xpath = '//tr/td[contains(text(), "' . $role_name . '")]/..';
        try {
            $element = $this->webdriver->element($this->webdriver->using('xpath')->value($row_xpath));
            return new RoleManagementTableRow($this->webdriver, $element);
        } catch (\Exception $e) {
            return null;
        }
    }
}
