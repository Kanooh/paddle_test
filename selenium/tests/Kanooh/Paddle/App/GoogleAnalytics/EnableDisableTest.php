<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\GoogleAnalytics\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\GoogleAnalytics;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\GoogleAnalytics;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleGoogleAnalytics\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Element\AppsOverviewPage\InstallPaddletModal;
use Kanooh\Paddle\Pages\Element\AppsOverviewPage\UninstallPaddletModal;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\GoogleAnalytics
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class EnableDisableTest extends EnableDisableTestBase
{
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

        $this->configurePage = new ConfigurePage($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getApp()
    {
        return new GoogleAnalytics;
    }

    /**
     * Tests the restore functionality.
     */
    public function testRestoreSettings()
    {
        $this->appService->enableAppUI($this->app);
        $this->configurePage->go();
        $this->configurePage->form->uaKey->fill('UA-55556666-2');
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
        $this->assertEquals('UA-55556666-2', $this->configurePage->form->uaKey->getContent());

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
        $this->assertEquals('UA-', $this->configurePage->form->uaKey->getContent());

        // Disable via UI and check if googleanalytics module is also disabled
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
        $this->assertFalse(module_exists('googleanalytics'));
    }
}
