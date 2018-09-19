<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Users\UsersPage.
 */

namespace Kanooh\Paddle\Pages\Admin\Users;

use Kanooh\Paddle\Pages\AdminPage;

/**
 * The User management page.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $rolesLink
 * @property UsersManagementPageForm $form
 * @property UsersManagementPageContextualToolbar $contextualToolbar
 */
class UsersPage extends AdminPage
{

    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/users';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'form':
                return new UsersManagementPageForm($this->webdriver, $this->webdriver->byId('views-form-users-overview-page'));
            case 'rolesLink':
                return $this->webdriver->byXPath('//ul/li[contains(@class, "ml-path-admin-users-roles")]/a');
            case 'contextualToolbar':
                return new UsersManagementPageContextualToolbar($this->webdriver);
        }

        return parent::__get($property);
    }
}
