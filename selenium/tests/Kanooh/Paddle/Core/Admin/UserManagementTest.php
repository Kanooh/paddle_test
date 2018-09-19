<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Core\Admin\UserManagementTest.
 */

namespace Kanooh\Paddle\Core\Admin;

use Kanooh\Paddle\Pages\Admin\DashboardPage\DashboardPage;
use Kanooh\Paddle\Pages\Admin\User\UserProfileEditPage;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\TestDataProvider\EmailTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests user management.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class UserManagementTest extends WebDriverTestCase
{
    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var DashboardPage
     */
    protected $dashboardPage;

    /**
     * @var EmailTestDataProvider
     */
    protected $emailTestDataProvider;

    /**
     * @var UserProfileEditPage
     */
    protected $userProfileEditPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->dashboardPage = new DashboardPage($this);
        $this->emailTestDataProvider = new EmailTestDataProvider();
        $this->userProfileEditPage = new UserProfileEditPage($this);
        $this->userSessionService = new UserSessionService($this);

        $drupal = new DrupalService();
        $drupal->bootstrap($this);

        $this->userSessionService->login('SiteManager');
    }

    /**
     * Tests the user management block.
     *
     * @group userProfile
     */
    public function testUserManagementBlock()
    {
        $this->dashboardPage->go();
        $this->assertTrue($this->dashboardPage->userManagementBlock->isPresent());

        // Set a real name for the user.
        $this->userProfileEditPage->go($this->userSessionService->getCurrentUserId());
        $realname = $this->alphanumericTestDataProvider->getValidValue();
        $this->userProfileEditPage->form->realName->fill($realname);
        // Fully complete the profile form.
        $this->userProfileEditPage->form->completeUserProfile();
        $this->userProfileEditPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The changes have been saved.');

        // Assert that the username shown in the block is the real name.
        $this->dashboardPage->go();
        $this->assertEquals($realname, $this->dashboardPage->userManagementBlock->userName->text());

        // Check the current user image.
        $account = user_load($this->userSessionService->getCurrentUserId(), true);
        $picture = empty($account->picture) ? 'placeholder.png' : $account->picture->filename;

        // Assert that the picture shown is the placeholder.
        $this->assertContains(
            $picture,
            $this->dashboardPage->userManagementBlock->profilePicture->attribute('src')
        );
        $this->moveto($this->dashboardPage->userManagementBlock->userName);
        $this->waitUntilTextIsPresent('Log out');
        $this->assertContains(
            'user/logout',
            $this->dashboardPage->userManagementBlock->userLinks->linkLogout->attribute('href')
        );

        $this->assertContains(
            'user/' . $this->userSessionService->getCurrentUserId() . '/edit',
            $this->dashboardPage->userManagementBlock->userLinks->linkUser->attribute('href')
        );
    }

    /**
     * Tests the editing of your own profile.
     *
     * @group userProfile
     */
    public function testProfileEditing()
    {
        $this->userProfileEditPage->go($this->userSessionService->getCurrentUserId());

        // Assert the prefilled fields.
        $this->assertEquals('demo', $this->userProfileEditPage->form->userName->getContent());
        $this->assertTextPresent('Site Manager', $this->userProfileEditPage->form->roles->getWebdriverElement());
        $this->assertTextPresent('Chief Editor', $this->userProfileEditPage->form->roles->getWebdriverElement());

        // Clear the email and name fields.
        $this->userProfileEditPage->form->email->clear();
        $this->userProfileEditPage->form->realName->clear();
        // Save the page and verify that the required fields are indeed
        // required.
        $this->userProfileEditPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('E-mail address field is required.');
        $this->waitUntilTextIsPresent('Name field is required.');

        // Set the real name and email address.
        $real_name = $this->alphanumericTestDataProvider->getValidValue();
        $this->userProfileEditPage->form->realName->fill($real_name);
        $email = $this->emailTestDataProvider->getValidValue();
        $this->userProfileEditPage->form->email->fill($email);
        // We need to set the current password to be able to change the email.
        $this->userProfileEditPage->form->currentPassword->fill('demo');

        // Set the telephone number.
        $phone_number = '015565656';
        $this->userProfileEditPage->form->phoneNumber->fill($phone_number);

        // Set a profile picture. We do it like this because the upload field is
        // not the same as other "normal" file fields since it does not use an
        // explicit upload button nor a remove button.
        $file = $this->file(dirname(__FILE__) . '/../../assets/budapest.jpg');
        $this->userProfileEditPage->form->profilePicture->fileField->value($file);

        $this->userProfileEditPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The changes have been saved.');

        // Verify that the placeholder image is no longer there. We cannot
        // specifically search for the name of the actual file because it is
        // being renamed internally.
        $this->assertNotContains(
            'placeholder.png',
            $this->userProfileEditPage->userManagementBlock->profilePicture->attribute('src')
        );

        // Verify that the real name is now shown in the user management block.
        $this->assertEquals($real_name, $this->dashboardPage->userManagementBlock->userName->text());

        // Verify that the fields are correct after saving.
        $this->assertEquals($phone_number, $this->userProfileEditPage->form->phoneNumber->getContent());
        $this->assertEquals($email, $this->userProfileEditPage->form->email->getContent());
        $this->assertEquals($real_name, $this->userProfileEditPage->form->realName->getContent());
    }
}
