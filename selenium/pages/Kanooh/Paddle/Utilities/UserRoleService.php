<?php

/**
 * @file
 * Contains Kanooh\Paddle\Utilities\UserRoleService.
 */

namespace Kanooh\Paddle\Utilities;

use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Utility class to manage user roles.
 */
class UserRoleService
{
    /**
     * The Selenium webdriver.
     *
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * Construct a UserRoleService object.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        $this->webdriver = $webdriver;

        $drupal = new DrupalService();
        $drupal->bootstrap($webdriver);
    }

    /**
     * Create a new user role in Drupal.
     *
     * @param string $role_name
     *   The name of the role.
     *
     * @return int|bool
     *   Status constant indicating if role was created or updated.
     *   Failure to write the user role record will return FALSE. Otherwise.
     *   SAVED_NEW or SAVED_UPDATED is returned depending on the operation
     *   performed.
     */
    public function createUserRole($role_name)
    {
        $role = new \stdClass();
        $role->name = $role_name;
        return user_role_save($role);
    }
}
