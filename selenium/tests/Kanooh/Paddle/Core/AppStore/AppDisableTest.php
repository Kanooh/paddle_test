<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\AppStore\AppDisableTest.
 */

namespace Kanooh\Paddle\Core\AppStore;

use Kanooh\Paddle\Apps\Carousel;
use Kanooh\Paddle\Apps\Embed;
use Kanooh\Paddle\Apps\FlyOutMenu;
use Kanooh\Paddle\Apps\GoogleCustomSearch;
use Kanooh\Paddle\Apps\OrganizationalUnit;
use Kanooh\Paddle\Pages\Admin\Apps\AppsOverviewPage\AppsOverviewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Test the disabled apps.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class AppDisableTest extends WebDriverTestCase
{
    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var AppsOverviewPage
     */
    protected $appsOverviewPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        $this->appsOverviewPage = new AppsOverviewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);

        $drupal = new DrupalService();
        $drupal->bootstrap($this);

        // Set the subscription to instap because that it the only 1 where we
        // can test the disabled button for for now.
        variable_set('paddle_store_subscription_type', 'instap');

        $this->userSessionService->login('SiteManager');
    }

    /**
     * Tests disabled activate button.
     *
     * @group store
     */
    public function testDisabledActivateButton()
    {
        // Go to the apps overview page.
        $this->appsOverviewPage->go();

        // Disable all apps first so we have more control over results of the
        // status filter.
        $this->appService->disableAppsByMachineNames(array_keys($this->appsOverviewPage->apps));

        // Enable 5 free apps.
        $enabled_apps = array(
            'paddle_carousel' => new Carousel,
            'paddle_embed' => new Embed,
            'paddle_fly_out_menu' => new FlyOutMenu,
            'paddle_google_custom_search' => new GoogleCustomSearch,
            'paddle_organizational_unit' => new OrganizationalUnit,
        );

        foreach ($enabled_apps as $app) {
            $this->appService->enableApp($app);
        }

        // Go to the apps overview page.
        $this->appsOverviewPage->go();
        $apps = $this->appsOverviewPage->getApps();

        // Verify that for all the free apps which have not been enabled there is a
        // disabled install button.
        foreach ($apps as $machine_name => $app) {
            if (!array_key_exists($machine_name, $enabled_apps) && !$app->isPaid) {
                $message = "Install button for $machine_name app is disabled";
                $this->assertTrue($app->checkDisabledInstallButton(), $message);
            }
        }
    }
}
