<?php

/**
 * @file
 * Contains Kanooh\Paddle\Utilities\UserService.
 */

namespace Kanooh\Paddle\Utilities;

use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\TestDataProvider\EmailTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Utility class to manager user accounts.
 */
class UserService
{
    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var EmailTestDataProvider
     */
    protected $emailTestDataProvider;

    /**
     * The Selenium webdriver.
     *
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * Construct an UserService object.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        $this->webdriver = $webdriver;

        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->emailTestDataProvider = new EmailTestDataProvider();

        $drupal = new DrupalService();
        $drupal->bootstrap($webdriver);
    }

    /**
     * Create a new user in Drupal.
     *
     * @param array $edit
     *   An array of user data.
     *
     * @return bool|\stdClass
     *   A fully-loaded $user object upon successful save or false if the save failed.
     */
    public function createUser($edit = array())
    {
        // Provide default values for the user.
        $edit = $edit + array(
            'name' => $this->alphanumericTestDataProvider->getValidValue(),
            'pass' => 'demo',
            'mail' => $this->emailTestDataProvider->getValidValue(),
            'access' => '0',
            'status' => 1,
        );

        return user_save(null, $edit);
    }

    /**
     * Assign roles to an user.
     *
     * @param object $account
     *   The user to add roles to.
     * @param array $role_names
     *   An array of role names.
     *
     * @return bool|\stdClass
     *   A fully-loaded $user object upon successful save or false if the save failed.
     */
    public function assignRolesToUser($account, array $role_names)
    {
        $roles = $account->roles;
        foreach ($role_names as $name) {
            $role = user_role_load_by_name($name);
            $roles[$role->rid] = $role->name;
        }

        $edit = array(
            'roles' => $roles,
        );

        return (bool) user_save($account, $edit);
    }
}
