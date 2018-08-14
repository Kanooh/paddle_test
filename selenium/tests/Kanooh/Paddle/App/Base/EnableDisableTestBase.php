<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Base\EnableDisableTestBase.
 */

namespace Kanooh\Paddle\App\Base;

use Kanooh\Paddle\Apps\AppInterface;
use Kanooh\Paddle\Pages\Admin\Apps\AppsOverviewPage\AppsOverviewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for testing the enabling and disabling of an app.
 */
abstract class EnableDisableTestBase extends WebDriverTestCase
{
    /**
     * The page that shows the overview of apps.
     *
     * @var AppsOverviewPage
     */
    protected $appsOverviewPage;

    /**
     * The App being tested.
     *
     * @var AppInterface
     */
    protected $app;

    /**
     * The app service.
     *
     * @var AppService
     */
    protected $appService;

    /**
     * The user session helper.
     *
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Instantiate the classes that will be used in the test.
        $this->appsOverviewPage = new AppsOverviewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);
        $this->app = $this->getApp();

        // Disable the app if it happens to be enabled.
        $this->appService->disableAppsByMachineNames(array($this->app->getModuleName()));

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Provides the App that is being tested.
     *
     * @return \Kanooh\Paddle\Apps\AppInterface
     *   The app.
     */
    abstract public function getApp();

    /**
     * Test enabling and disabling an app through the UI.
     *
     * @group store
     * @group enableDisableApp
     */
    public function testEnableDisable()
    {
        $this->appsOverviewPage->go();

        try {
            $app_element = $this->appsOverviewPage->appElement($this->app);
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            $this->fail('App is not available on the apps overview page');
            return;
        }

        // Install the app.
        $this->appService->enableAppUI($this->app);

        // Refresh the overview page.
        $this->appsOverviewPage->go();

        // Verify that the status is correct and that the status text says that
        // the app is installed. Transform the status text to uppercase in case
        // it isn't because we shouldn't really test capitalization.
        $app_element = $this->appsOverviewPage->appElement($this->app);

        $app_info = paddle_apps_app_load($app_element->machineName);
        if ($app_info->restorable) {
            $this->assertEquals('UNINSTALL', strtoupper($app_element->statusText));
        } else {
            $this->assertEquals('INSTALLED', strtoupper($app_element->statusText));
        }
        $this->assertEquals(1, $app_element->status);

        // Verify the configure link is visible or not, depending on whether the
        // app is configurable.
        $this->assertEquals($this->app->isConfigurable(), $app_element->checkConfigureButton($this->app), 'The presence of the "Configure" button matches the configuration in the App.');

        // When an app has a configuration page, verify that the management menu
        // level 2 is present.
        if ($this->app->isConfigurable()) {
            $app_element->links->linkConfigure->click();
            $this->waitUntilElementIsDisplayed('//div[@id="block-paddle-menu-display-management-level-2"]');
        }

        // Uninstall the app.
        $this->appService->disableAppsByMachineNames(array($this->app->getModuleName()));

        // Refresh the overview page.
        $this->appsOverviewPage->go();

        // Verify that the status is correct.
        $app_element = $this->appsOverviewPage->appElement($this->app);
        $this->assertEquals(0, $app_element->status);
    }
}
