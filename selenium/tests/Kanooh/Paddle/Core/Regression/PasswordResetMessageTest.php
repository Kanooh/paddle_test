<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Regression\PasswordResetMessageTest.
 */

namespace Kanooh\Paddle\Core\Regression;

use Kanooh\Paddle\Pages\Admin\DashboardPage\DashboardPage;
use Kanooh\Paddle\Pages\User\LoginPage\LoginPage;
use Kanooh\Paddle\Pages\User\PasswordPage\PasswordPage;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\TestDataProvider\EmailTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests that the standard Drupal password reset confirmation message
 * is shown.
 *
 * @see https://one-agency.atlassian.net/browse/KANWEBS-2793
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PasswordResetMessageTest extends WebDriverTestCase
{
    /**
     * The Dashboard page.
     *
     * @var DashboardPage
     */
    protected $dashboardPage;

    /**
     * The login page.
     *
     * @var LoginPage
     */
    protected $loginPage;

    /**
     * The password page.
     *
     * @var PasswordPage
     */
    protected $passwordPage;

    /**
     * The alphanumeric test data provider.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * The email test data provider.
     *
     * @var EmailTestDataProvider
     */
    protected $emailTestDataProvider;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->dashboardPage = new DashboardPage($this);
        $this->loginPage = new LoginPage($this);
        $this->passwordPage = new PasswordPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->emailTestDataProvider = new EmailTestDataProvider();

        $drupal = new DrupalService();
        $drupal->bootstrap($this);
    }

    /**
     * Test that the password reset confirmation message is shown.
     *
     * @group regression
     * @group userProfile
     */
    public function testResetMailMessage()
    {
        // Create a new user so we have an e-mail to use. The demo users don't
        // have e-mails.
        $role = user_role_load_by_name('Read Only');
        $account = user_save(
            drupal_anonymous_user(),
            array(
                'name' => $this->alphanumericTestDataProvider->getValidValue(),
                'mail' => $this->emailTestDataProvider->getValidValue(),
                'pass' => user_password(),
                'status' => 1,
            )
        );

        user_multiple_role_edit(array($account->uid), 'add_role', $role->rid);

        // Go the password page.
        $this->passwordPage->go();

        // Ask to reset the password.
        $this->passwordPage->passwordForm->name->fill($account->mail);
        $this->passwordPage->passwordForm->submit->click();
        $this->loginPage->checkArrival();

        // Look for the message.
        $this->assertTextPresent('Further instructions have been sent to your e-mail address.');

        // Delete the user.
        user_delete($account->uid);
    }

    /**
     * Test that the reset confirmation message is shown when logged in.
     *
     * @group regression
     * @group userProfile
     */
    public function testResetMailMessageWhenLoggedIn()
    {
      // Create a new user so we have an e-mail to use. The demo users don't
      // have e-mails.
        $role = user_role_load_by_name('Read Only');

        $edit = array(
            'name' => $this->alphanumericTestDataProvider->getValidValue(),
            'mail' => $this->emailTestDataProvider->getValidValue(),
            'pass' => user_password(),
            'status' => 1,
        );

        $account = user_save(drupal_anonymous_user(), $edit);
        user_multiple_role_edit(array($account->uid), 'add_role', $role->rid);

        // Log in as the newly created user.
        $this->loginPage->go();
        $this->loginPage->loginForm->name->fill($edit['name']);
        $this->loginPage->loginForm->pass->fill($edit['pass']);
        $this->loginPage->loginForm->submit->click();
        $this->waitUntilElementIsDisplayed('//body[contains(@class, "logged-in")]');

        // Go the password page.
        $this->passwordPage->go();

        // Verify the form.
        $this->assertTextPresent('Request new password');
        $this->passwordPage->passwordForm->submit->click();

        // Look for the message.
        $this->waitUntilTextIsPresent('Further instructions have been sent to your e-mail address.');

        // Delete the user.
        user_delete($account->uid);
    }
}
