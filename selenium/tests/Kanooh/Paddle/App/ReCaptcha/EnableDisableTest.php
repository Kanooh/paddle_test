<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ReCaptcha\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\ReCaptcha;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\ReCaptcha;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleReCaptcha\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Element\AppsOverviewPage\InstallPaddletModal;
use Kanooh\Paddle\Pages\Element\AppsOverviewPage\UninstallPaddletModal;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\ReCaptcha
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class EnableDisableTest extends EnableDisableTestBase
{
    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->configurePage = new ConfigurePage($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getApp()
    {
        return new ReCaptcha;
    }

    /**
     * Tests the restore functionality.
     */
    public function testRestoreSettings()
    {
        // Only site managers can configure the API key for reCAPTCHA.
        $this->userSessionService->switchUser('SiteManager');

        $site_key = $this-$this->alphanumericTestDataProvider->getValidValue();
        $secret_key = $this-$this->alphanumericTestDataProvider->getValidValue();
        $this->appService->enableAppUI($this->app);
        $this->configurePage->go();
        $this->configurePage->form->reCaptchaSiteKey->fill($site_key);
        $this->configurePage->form->reCaptchaSecretKey->fill($secret_key);
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The configuration options have been saved.');

        $this->appsOverviewPage->go();
        $app_element = $this->appsOverviewPage->appElement($this->app);
        $this->assertEquals('UNINSTALL', strtoupper($app_element->statusText));
        $this->assertEquals(1, $app_element->status);
        $this->appService->disableAppsByMachineNames(array($this->app->getModuleName()));

        // Re-enable & check if key is still the same.
        $this->appsOverviewPage->go();
        $app_element = $this->appsOverviewPage->appElement($this->app);
        $this->assertEquals('INSTALL', strtoupper($app_element->statusText));
        $this->assertEquals(0, $app_element->status);
        $this->appService->enableAppUI($this->app);
        $this->configurePage->go();
        $this->assertEquals($site_key, $this->configurePage->form->reCaptchaSiteKey->getContent());
        $this->assertEquals($secret_key, $this->configurePage->form->reCaptchaSecretKey->getContent());

        $this->appsOverviewPage->go();
        $app_element = $this->appsOverviewPage->appElement($this->app);
        $this->assertEquals('UNINSTALL', strtoupper($app_element->statusText));
        $this->assertEquals(1, $app_element->status);
        $this->appService->disableAppsByMachineNames(array($this->app->getModuleName()));

        // Clean install and check if key is gone.
        $this->appsOverviewPage->go();
        $app_element = $this->appsOverviewPage->appElement($this->app);
        $this->assertEquals('INSTALL', strtoupper($app_element->statusText));
        $this->assertEquals(0, $app_element->status);
        $app_element = $this->appsOverviewPage->appElement($this->app);
        $app_element->activationButton->click();
        $modal = new InstallPaddletModal($this);
        $modal->waitUntilOpened();
        $modal->form->cleanInstallButton->click();
        $modal->waitUntilClosed();
        $this->appsOverviewPage->checkArrival();
        $this->appService->processAppQueue($this->app);
        $this->configurePage->go();
        $this->assertEquals('', $this->configurePage->form->reCaptchaSiteKey->getContent());
        $this->assertEquals('', $this->configurePage->form->reCaptchaSecretKey->getContent());
        // Disable via UI and check if recaptcha and captcha modules are also disabled
        $this->appsOverviewPage->go();
        $app_element = $this->appsOverviewPage->appElement($this->app);
        $this->assertEquals('UNINSTALL', strtoupper($app_element->statusText));
        $this->assertEquals(1, $app_element->status);
        $app_element = $this->appsOverviewPage->appElement($this->app);
        $app_element->activationButton->click();
        $modal = new UninstallPaddletModal($this);
        $modal->waitUntilOpened();
        $modal->form->uninstallButton->click();
        $modal->waitUntilClosed();
        $this->appsOverviewPage->checkArrival();
        $this->appService->processAppQueue($this->app);
        $this->assertFalse(module_exists('recaptcha'));
        $this->assertFalse(module_exists('captcha'));
    }
}
