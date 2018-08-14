<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Multilingual\InterfaceLanguageTest
 */

namespace Kanooh\Paddle\App\Multilingual;

use Kanooh\Paddle\Apps\Multilingual;
use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPage as ContentManagerPage;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMultilingual\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\User\UserProfileAddPage;
use Kanooh\Paddle\Pages\Admin\Users\UsersPage;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\MultilingualService;
use Kanooh\Paddle\Utilities\UserService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\Paddle\Pages\Admin\User\UserProfileEditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndNodeViewPage;
use Kanooh\Paddle\Pages\Element\PreviewToolbar\PreviewToolbar;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Checks that the interface language always remains the default language.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class InterfaceLanguageTest extends WebDriverTestCase
{
    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;
    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var FrontEndNodeViewPage
     */
    protected $frontEndNodeViewPage;
    /**
     * @var ContentManagerPage
     */
    protected $contentManagerPage;

    /**
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * @var UserProfileAddPage
     */
    protected $userProfileAddPage;

    /**
     * @var UserProfileEditPage
     */
    protected $userProfileEditPage;

    /**
     * @var UsersPage
     */
    protected $usersPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Instantiate some classes to use in the test.
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->contentManagerPage = new ContentManagerPage($this);
        $this->layoutPage = new LayoutPage($this);
        $this->userProfileAddPage = new UserProfileAddPage($this);
        $this->usersPage = new UsersPage($this);
        $this->frontEndNodeViewPage = new FrontEndNodeViewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->userProfileEditPage = new UserProfileEditPage($this);
        $this->userService = new UserService($this);
        $this->configurePage = new ConfigurePage($this);

        // Log in as site manager.
        $this->userSessionService->login('SiteManager');

        // Enable the app if it is not enabled yet.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Multilingual);
    }

    /**
     * Tests that the interface language follows the user preferred language.
     */
    public function testInterfaceLanguageDependsOnUserPreferredLanguage()
    {
        // Make sure the tests have the expected multilingual configuration.
        MultilingualService::setPaddleTestDefaults($this);

        // Dutch should be the current interface language and Dutch is the content language.
        // The site default language is Dutch by default, but the users default language is set to English in the tests.
        // See profiles/paddle/create_demo_users.sh & KANWEBS-5265.
        $this->contentManagerPage->go();
        $this->assertEquals('nl', $this->contentManagerPage->getInterfaceLanguage());
        $this->assertEquals('nl', $this->contentManagerPage->languageSwitcher->getActiveLanguage());

        // Switch now to English. The interface should remain Dutch while the
        // content language should become English.
        $this->contentManagerPage->languageSwitcher->switchLanguage('en');
        $this->contentManagerPage->checkArrival();
        $this->assertEquals('nl', $this->contentManagerPage->getInterfaceLanguage());
        $this->assertEquals('en', $this->contentManagerPage->languageSwitcher->getActiveLanguage());

        // Check the modal content language on a non-Dutch page. Backend
        // language on all pages should be dependant on your language settings.
        $nid = $this->contentCreationService->createBasicPage();
        $this->contentCreationService->changeNodeLanguage($nid, 'en');
        $this->layoutPage->go($nid);
        $this->layoutPage->display->getRandomRegion()->buttonAddPane->click();
        $modal = new AddPaneModal($this);
        $modal->waitUntilOpened();
        $this->assertEquals('nl', $modal->addPaneList->attribute('lang'));
        $modal->close();
        $modal->waitUntilClosed();
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The changes have been saved.');

        // Change user preferred language to NL.
        $this->userProfileEditPage->go($this->userSessionService->getCurrentUserId());
        $this->userProfileEditPage->form->language->dutch->select();
        $this->userProfileEditPage->form->completeUserProfile();
        $this->userProfileEditPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The changes have been saved.');

        $this->assertEquals('nl', $this->contentManagerPage->getInterfaceLanguage());

        // Make sure we only see EN, FR and NL.
        $this->userProfileEditPage->go($this->userSessionService->getCurrentUserId());
        $this->assertTextPresent('Dutch (Nederlands)');
        $this->assertTextPresent('English');
        $this->assertTextPresent('French (Français)');
        $this->assertTextNotPresent('German (Deutsch)');
        //  Fill to avoid error messages.
        $this->userProfileEditPage->form->completeUserProfile();
        $this->userProfileEditPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The changes have been saved.');

        // Even if we enable more languages, the uer profile language choces will stay the same.
        $this->configurePage->go();
        $this->configurePage->checkArrival();

        // Enable an additional language.
        $this->configurePage->form->enableBulgarian->check();

        // Save the configuration.
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');
        $this->configurePage->checkArrival();

        $this->userProfileEditPage->go($this->userSessionService->getCurrentUserId());
        $this->assertTextPresent('Dutch (Nederlands)');
        $this->assertTextPresent('English');
        $this->assertTextPresent('French (Français)');
        $this->assertTextNotPresent('German (Deutsch)');
        $this->assertTextNotPresent('Bulgarian (Български)');

        // Set user language back to English to avoid problems for other tests.
        $this->userProfileEditPage->form->language->english->select();
        $this->userProfileEditPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The changes have been saved.');
    }

    /**
     * Test defaults for users.
     */
    public function testUserDefaultLanguage()
    {
        // Make sure the tests have the expected multilingual configuration.
        MultilingualService::setPaddleTestDefaults($this);

        $this->usersPage->go();

        // Click on add user button.
        $this->usersPage->contextualToolbar->buttonAdd->click();
        $this->userProfileAddPage->checkArrival();

        $user_name = $this->alphanumericTestDataProvider->getValidValue();
        $pass = $this->alphanumericTestDataProvider->getValidValue();
        $email = $this->alphanumericTestDataProvider->getValidValue(8, true, true) . '@kanooh.be';

        // Fill the user registration form.
        $this->userProfileAddPage->form->userName->fill($user_name);
        $this->userProfileAddPage->form->password->fill($pass);
        $this->userProfileAddPage->form->confirmPassword->fill($pass);
        $this->userProfileAddPage->form->email->fill($email);
        $this->userProfileAddPage->form->languageSettings->french->select();
        $this->userProfileAddPage->contextualToolbar->buttonSave->click();
        $this->usersPage->checkArrival();

        $user = user_load_by_name($user_name);

        // Verify that the admin language for both users has been set correctly.
        $query = db_select('admin_language', 'al')
            ->fields('al')
            ->condition('uid', $user->uid);
        $result = $query->execute()->fetchAll();
        $this->assertCount(1, $result);
        $this->assertEquals('fr', $result[0]->language);
    }

    /**
     * Test preview toolbar
     */
    public function testPreviewToolbar()
    {
        // Create a basic page.
        $nid = $this->contentCreationService->createBasicPage();
        $this->frontEndNodeViewPage->go($nid);

        $previewToolbar = new PreviewToolbar($this);

        // Change user preferred language to NL.
        $this->userProfileEditPage->go($this->userSessionService->getCurrentUserId());
        $this->userProfileEditPage->form->language->dutch->select();
        $this->userProfileEditPage->form->completeUserProfile();
        $this->userProfileEditPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The changes have been saved.');
        $this->frontEndNodeViewPage->go($nid);

        // Assert that the preview toolbar have NL as language.
        $this->assertEquals('nl', $previewToolbar->toolbarContent->attribute('data-language'));

        // Change user preferred language to FR.
        $this->userProfileEditPage->go($this->userSessionService->getCurrentUserId());
        $this->userProfileEditPage->form->language->french->select();
        $this->userProfileEditPage->form->completeUserProfile();
        $this->userProfileEditPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The changes have been saved.');
        $this->frontEndNodeViewPage->go($nid);

        // Assert that the preview toolbar have FR as language.
        $this->assertEquals('fr', $previewToolbar->toolbarContent->attribute('data-language'));
    }
}
