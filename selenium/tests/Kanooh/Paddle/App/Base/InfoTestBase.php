<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Base\InfoTestBase.
 */

namespace Kanooh\Paddle\App\Base;

use Kanooh\Paddle\Apps\AppInterface;
use Kanooh\Paddle\Pages\Admin\Apps\AppsOverviewPage\AppsOverviewPage;
use Kanooh\Paddle\Pages\Admin\Apps\InfoPage\InfoPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for testing the info page of an app.
 */
abstract class InfoTestBase extends WebDriverTestCase
{
    /**
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
     * @var InfoPage
     */
    protected $infoPage;

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
        $this->app = $this->getApp();
        $this->infoPage = new InfoPage($this, $this->app);

        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp($this->app);

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
     * @group InfoTestBase
     */
    public function testInfoPage()
    {
        $this->infoPage->go();
        $this->infoPage->adminMenuLinks->checkLinks(array('Dashboard', 'Structure', 'Content', 'PaddleStore'));
        $this->assertTrue($this->infoPage->checkDetailedDescription());

        // Get the app info.
        $app = paddle_apps_app_load($this->app->getModuleName());

        $this->assertFAQ($app);
        $this->assertVendor($app);
    }

    /**
     * Asserts the data displayed on the info page concerning the vendor.
     *
     * @param object $app
     *   The app to test the vendor for.
     */
    public function assertVendor($app)
    {
        $this->infoPage->go();

        $vendor_info = $this->infoPage->getVendorInfo();
        if (!empty($app->paddle['vendor'])) {
            $this->assertEquals($app->paddle['vendor'], $vendor_info['vendor']);
        }

        if (!empty($app->paddle['vendor_link'])) {
            // Trim any trailing slashes to make sure the links can be compared.
            $this->assertEquals(trim($app->paddle['vendor_link'], '/'), trim($vendor_info['link'], '/'));
        }
    }

    /**
     * Asserts the data displayed on the info page concerning the FAQ.
     *
     * @param object $app
     *   The app to test the vendor for.
     */
    public function assertFAQ($app)
    {
        $this->infoPage->go();

        $actual_faq = $this->infoPage->getFAQ();
        foreach ($app->faq as $title => $link) {
            $this->assertTrue(isset($actual_faq[$title]));
            $this->assertEquals($link, $actual_faq[$title]);
        }
    }
}
