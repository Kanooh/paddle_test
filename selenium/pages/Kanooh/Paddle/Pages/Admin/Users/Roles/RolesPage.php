<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Users\Roles\RolesPage.
 */

namespace Kanooh\Paddle\Pages\Admin\Users\Roles;

use Kanooh\Paddle\Pages\AdminPage;

/**
 * The Role management page.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $addRoleLink
 * @property RoleManagementTable $table
 */
class RolesPage extends AdminPage
{

    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/users/roles';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'addRoleLink':
                return $this->webdriver->byXPath('//ul[contains(@id, "contextual-actions-list")]/li/a[contains(@id, "add-role-button")]');
            case 'table':
                return new RoleManagementTable($this->webdriver, '//table[contains(@class, "views-table")]');
        }

        return parent::__get($property);
    }
}
