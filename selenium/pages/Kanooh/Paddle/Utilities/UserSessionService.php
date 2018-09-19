<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Utilities\UserSessionService.
 */

namespace Kanooh\Paddle\Utilities;

use Kanooh\Paddle\Pages\User\LoginPage\LoginPage;
use Kanooh\Paddle\Pages\User\LogoutPage\LogoutPage;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Utility class for managing user sessions.
 */
class UserSessionService
{
    /**
     * The Selenium webdriver.
     *
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * The name of the currently logged in user.
     *
     * @var string
     */
    protected $currentUser;

    /**
     * The login page.
     *
     * @var LoginPage
     */
    protected $loginPage;

    /**
     * The logout page.
     *
     * @var LogoutPage
     */
    protected $logoutPage;

    /**
     * Constructs a UserSessionService object.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        $this->webdriver = $webdriver;

        $this->currentUser = null;

        $this->loginPage = new LoginPage($webdriver);
        $this->logoutPage = new LogoutPage($webdriver);
    }

    /**
     * Log in as the given user.
     *
     * @param string $username
     *   The unique name that is used to identify the user.
     * @param boolean $global
     *   If set to true, the global user variable will also be switched to the
     *   new logged in user so the bootstrapped Drupal functions think we're
     *   running as the same user.
     */
    public function login($username, $global = false)
    {
        global $user;

        $credentials = $this->getUserCredentials($username);

        if ($global) {
            $user = user_load_by_name($credentials['username']);
        }

        $this->loginPage->go();
        $this->loginPage->loginForm->name->fill($credentials['username']);
        $this->loginPage->loginForm->pass->fill($credentials['password']);
        $this->loginPage->loginForm->submit->click();
        $this->webdriver->waitUntilElementIsDisplayed('//body[contains(@class, "logged-in")]');

        $this->currentUser = $username;
    }

    /**
     * Log in as a custom made user.
     *
     * @param string $username
     *   The unique name that is used to identify the user.
     * @param string $password
     *   The password of the user to log in with.
     */
    public function customLogin($username, $password = 'demo')
    {
        $this->loginPage->go();
        $this->loginPage->loginForm->name->fill($username);
        $this->loginPage->loginForm->pass->fill($password);
        $this->loginPage->loginForm->submit->click();
        $this->webdriver->waitUntilElementIsDisplayed('//body[contains(@class, "logged-in")]');

        $this->currentUser = $username;
    }

    /**
     * Returns the login credentials for a given user.
     *
     * @param string $user
     *   The unique name that is used to identify the user.
     *
     * @return array
     *   An associative array of user credentials, with the following keys:
     *   - username
     *   - password
     */
    protected function getUserCredentials($user)
    {
        $credentials = $this->userCredentials();

        return isset($credentials[$user]) ? $credentials[$user] : false;
    }

    /**
     * Returns a list of test user credentials.
     *
     * @todo This is currently a hardcoded list, this should be sourced from a
     *   list that is supplied by the test environment.
     *
     * @return array
     *   An associative array of user credentials, keyed by a unique name. Each
     *   set of credentials is an associative array with the following keys:
     *   - username
     *   - password
     *
     * @deprecated
     */
    protected function userCredentials()
    {
        return array(
            'ChiefEditor' => array(
                'username' => 'demo_chief_editor',
                'password' => 'demo',
            ),
            'Editor' => array(
                'username' => 'demo_editor',
                'password' => 'demo',
            ),
            'SiteManager' => array(
                'username' => 'demo',
                'password' => 'demo',
            ),
            'ReadOnly' => array(
                'username' => 'demo_read_only',
                'password' => 'demo',
            ),
        );
    }

    /**
     * Logs out the current user trough the UI.
     */
    public function logoutViaUI()
    {
        // When logged in, the logout page redirects to the front page, after
        // logging out. So, don't use $this->logoutPage->go() because that
        // calls checkPath() and thus would fail, if logged in.

        // Log out.
        $this->webdriver->url($this->logoutPage->getPath());

        // Ensure that we are not logged in.
        $this->webdriver->assertFalse($this->loggedIn());

        // Update the property of this class that represents the current user.
        $this->currentUser = null;
    }

    /**
     * Logs out the current user.
     */
    public function logout()
    {
        $this->clearCookies();
    }

    /**
     * Determine if a user is already logged in.
     *
     * @return boolean
     *   Returns TRUE if a user is logged in.
     */
    public function loggedIn()
    {
        // Check if the Drupal "logged-in" class is present on the body.
        try {
            $this->webdriver->byCssSelector('body.logged-in');
            return true;
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // This test may fail if the driver did not load any site yet.
        }

        // If for some reason the page has a different template, check if the login
        // form is shown on the /user/login page.
        $this->loginPage->go();
        if (!$this->loginPage->checkClassPresent('user-login-form')) {
            return true;
        }

        return false;
    }

    /**
     * Clears the session by removing all browser cookies.
     *
     * This can be used before switching to another user without logging out
     * the current user.
     */
    public function clearCookies()
    {
        $session = $this->webdriver->prepareSession();
        $session->cookie()->clear();

        $this->currentUser = null;
    }

    /**
     * Allows to execute code as a given user.
     *
     * This function will log the current user back in after executing $callable
     * as the given user. Please note the browser will not return to the
     * original URL after logging back in.
     *
     * @param string $user
     *   The name of the user to run the code as.
     * @param callable $callable
     *   The callable code.
     */
    public function runAsUser($user, callable $callable)
    {
        $initial_user = $this->currentUser;

        if ($initial_user !== $user) {
            if ($initial_user) {
                $this->logout();
            }

            $this->login($user);
        }

        $callable();

        if ($this->currentUser !== $initial_user) {
            $this->logout();

            if ($initial_user) {
                $this->login($initial_user);
            }
        }
    }

    /**
     * Returns a list of test user names.
     *
     * @return array
     *   An array containing the all usernames the system can log-in.
     */
    public function getUsernames()
    {
        return array_keys($this->userCredentials());
    }

    /**
     * Returns the current user.
     *
     * @return string
     *   Current user.
     */
    public function getCurrentUser()
    {
        return $this->currentUser;
    }

    /**
     * Returns the current user's Drupal username.
     *
     * @return string
     *   Current user's username or empty string if user not found.
     */
    public function getCurrentUserName()
    {
        $credentials = $this->userCredentials();

        return isset($credentials[$this->currentUser]['username']) ? $credentials[$this->currentUser]['username'] : '';
    }

    /**
     * Returns the current user's Drupal uid.
     *
     * @return int
     *   Current user's uid or false if user not found.
     */
    public function getCurrentUserId()
    {
        $username = $this->getCurrentUserName();
        if ($username) {
            $user = user_load_by_name($username);

            return $user->uid;
        }

        return false;
    }

    /**
     * Retrieves an user's Drupal uid by username.
     *
     * @param string $username
     *   The unique name that is used to identify the user.
     *
     * @return bool|int
     *   User's uid of false if user not found.
     */
    public function getUserId($username)
    {
        $credentials = $this->getUserCredentials($username);

        if ($credentials) {
            $account = user_load_by_name($credentials['username']);

            return $account->uid;
        }

        return false;
    }

    /**
     * Logs out the current user and logs-in a new one.
     *
     * If the new user is the same as the currently logged in user, nothing will
     * happen.
     *
     * @param string $user
     *    The username of the new user to login.
     */
    public function switchUser($user)
    {
        if ($user != $this->getCurrentUser()) {
            $this->logout();
            $this->login($user);
        }
    }
}
