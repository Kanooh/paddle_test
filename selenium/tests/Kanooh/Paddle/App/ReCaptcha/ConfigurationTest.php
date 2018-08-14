<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ReCaptcha\ConfigurationTest.
 */

namespace Kanooh\Paddle\App\ReCaptcha;

use Kanooh\Paddle\Apps\Formbuilder;
use Kanooh\Paddle\Apps\ReCaptcha;
use Kanooh\Paddle\Apps\SimpleContact;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleReCaptcha\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder\BuildFormPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\FormbuilderViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Node\ViewPage\FormbuilderViewPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;

/**
 * Performs configuration tests on the ReCaptcha paddlet.
 *
 * @package Kanooh\Paddle\App\ReCaptcha
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ConfigurationTest extends WebDriverTestCase
{
    /**
     * The administrative node view page.
     *
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var BuildFormPage
     */
    protected $buildFormPage;

    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var FormbuilderViewPage
     */
    protected $formbuilderViewPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * @var ViewPage
     */
    protected $viewPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Enable the app if it is not yet enabled.
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->buildFormPage = new BuildFormPage($this);
        $this->configurePage = new ConfigurePage($this);
        $this->formbuilderViewPage = new FormbuilderViewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->viewPage = new ViewPage($this);
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new ReCaptcha());
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as Site Manager.
        $this->userSessionService->login('SiteManager');
    }

    /**
     * Tests the saving of the paddlet's default settings and the configuration.
     */
    public function testConfiguration()
    {
        // Now check the configuration page and assert that no fields are required.
        $this->configurePage->go();
        $this->configurePage->form->reCaptchaSiteKey->clear();
        $this->configurePage->form->reCaptchaSecretKey->clear();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The configuration options have been saved.');

        // Assert that the the secret key field is required when you fill in the site key.
        $siteKey = $this->alphanumericTestDataProvider->getValidValue();
        $this->configurePage->form->reCaptchaSiteKey->fill($siteKey);
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Secret key field is required.');

        // Assert that the site key field is required when you fill in the secret key.
        $secretKey = $this->alphanumericTestDataProvider->getValidValue();
        $this->configurePage->form->reCaptchaSecretKey->fill($secretKey);
        $this->configurePage->form->reCaptchaSiteKey->clear();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Site key field is required.');

        // Asserts that the form can be saved if both fields are filled in.
        $this->configurePage->form->reCaptchaSiteKey->fill($siteKey);
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The configuration options have been saved.');
    }

    /**
     * Tests whether the captcha container is shown on webforms with fields displayed.
     */
    public function testCaptchaShown()
    {
        // Enable the Paddle Webform module.
        $this->appService->enableApp(new Formbuilder());
        // Create a formbuilder page.
        $nid = $this->contentCreationService->createFormbuilderPage();

        // Head to the view page.
        $this->formbuilderViewPage->go($nid);

        // Ensure that the captcha is not shown yet.
        try {
            $this->byCssSelector('.captcha');
            $this->fail('The Captcha should not be shown yet.');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // Everything is fine.
        }

        // Add a text field to the webform.
        $this->buildFormPage->go($nid);
        $this->buildFormPage->dragAndDrop('textfield');
        $this->waitUntilTextIsPresent('New textfield');
        $this->buildFormPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Publish the webform.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();

        // Head to the view page.
        $this->formbuilderViewPage->go($nid);

        // Ensure that the captcha is not shown yet since you are LOGGED IN.
        try {
            $this->byCssSelector('.captcha');
            $this->fail('The Captcha should not be shown yet.');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // Everything is fine.
        }

        // Log out.
        $this->userSessionService->logout();
        // Head to the view page.
        $this->formbuilderViewPage->go($nid);

        // NOW the captcha is shown.
        $this->byCssSelector('.captcha');
    }

    /**
     * Tests that the captcha works on all SimpleContact pages.
     */
    public function testCaptchaOnSimpleContactPages()
    {
        // Enable the Paddle Simple Contact module.
        $this->appService->enableApp(new SimpleContact);

        // Creates 2 nodes and publish them.
        $nid = $this->contentCreationService->createSimpleContact();
        $this->administrativeNodeViewPage->go($nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();
        $nid2 = $this->contentCreationService->createSimpleContact();
        $this->administrativeNodeViewPage->go($nid2);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        $this->viewPage->go($nid);

        // Ensure that the captcha is not shown yet since you are LOGGED IN.
        try {
            $this->byCssSelector('.captcha');
            $this->fail('The Captcha should not be shown yet.');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // Everything is fine.
        }

        // Log out.
        $this->userSessionService->logout();
        // Head to the view page.
        $this->viewPage->go($nid);

        // NOW the captcha is shown.
        $this->byCssSelector('.captcha');

        // Head to the second view page.
        $this->viewPage->go($nid2);

        // Verify that the captcha is shown.
        $this->byCssSelector('.captcha');
    }
}
