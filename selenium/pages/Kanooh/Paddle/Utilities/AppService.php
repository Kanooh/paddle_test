<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Utilities\AppService.
 */

namespace Kanooh\Paddle\Utilities;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\AppInterface;
use Kanooh\Paddle\Pages\Admin\Apps\AppsOverviewPage\AppsOverviewPage;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Utility class to help performing common interactions with apps.
 */
class AppService
{
    /**
     * The apps overview page.
     *
     * @var AppsOverviewPage
     */
    protected $appsPage;

    /**
     * The Drupal utility service.
     *
     * @var DrupalService
     */
    protected $drupalService;

    /**
     * The user session utility service.
     *
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * The Selenium webdriver.
     *
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * Constructs an AppService object.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param \Kanooh\Paddle\Utilities\UserSessionService $userSessionService
     *   The user session service.
     */
    public function __construct(WebDriverTestCase $webdriver, UserSessionService $userSessionService)
    {
        $this->webdriver = $webdriver;
        $this->userSessionService = $userSessionService;

        $this->appsPage = new AppsOverviewPage($webdriver);

        $this->drupalService = new DrupalService();
    }

    /**
     * Enables the given App.
     *
     * @param AppInterface $app
     *   The App that should be enabled.
     */
    public function enableApp(AppInterface $app)
    {
        $this->drupalService->bootstrap($this->webdriver);

        if (module_exists($app->getModuleName())) {
            // Nothing left to do if the app is already enabled.
            return;
        }

        if ($this->webdriver->getEnableAppsViaCron() == true) {
            // Queue app for installation so cron has something in the queue to
            // process.
            $this->queueAppForInstallation($app);

            $this->waitForCronToEnableAppsOrHitTimeout($app);
        } else {
            module_enable(array($app->getModuleName()));
            drupal_flush_all_caches();

            // Check if the app is actually installed.
            $this->webdriver->assertTrue(module_exists($app->getModuleName()));
        }

        if ($app->getModuleName() == 'paddle_holiday_participation') {
            elysia_cron_set_job_disabled('paddle_holiday_participation_cron', true);
        }
    }

    /**
     * Queues an app for installation.
     *
     * @param AppInterface $app
     *   The app that should be queued for installation.
     */
    public function queueAppForInstallation(AppInterface $app)
    {
        $this->drupalService->bootstrap($this->webdriver);

        // If the app is not already enabled then queue it for installation.
        if (!module_exists($app->getModuleName())) {
            paddle_apps_queue_add_command('activate', $app->getModuleName());
        }
    }

    /**
     * Queues an app for uninstallation.
     *
     * @param AppInterface $app
     *   The app that should be queued for uninstallation.
     */
    public function queueAppForUninstallation(AppInterface $app)
    {
        $this->drupalService->bootstrap($this->webdriver);

        // If the app is not already enabled then queue it for installation.
        if (!module_exists($app->getModuleName())) {
            paddle_apps_queue_add_command('deactivate', $app->getModuleName());
        }
    }

    /**
     * Enables the given App trough the UI.
     *
     * @param AppInterface $app
     *   The App that should be enabled.
     */
    public function enableAppUI(AppInterface $app)
    {
        $this->queueAppForInstallation($app);
        $this->processAppQueue($app);
        $this->webdriver->assertTrue(module_exists($app->getModuleName()));
    }

    /**
     * Disables an app.
     *
     * @param AppInterface $app
     *   The app that should be disabled.
     */
    public function disableApp(AppInterface $app)
    {
        $this->drupalService->bootstrap($this->webdriver);

        if (!module_exists($app->getModuleName())) {
            // Nothing left to do if the app is already disnabled.
            return;
        }

        if ($this->webdriver->getEnableAppsViaCron() == true) {
            // Queue app for uninstallation so cron has something in the queue to
            // process.
            $this->queueAppForUninstallation($app);

            $this->waitForCronToEnableAppsOrHitTimeout($app);
        } else {
            paddle_apps_deactivate_paddlet($app->getModuleName());

            // Check if the app is actually uninstalled.
            $this->webdriver->assertFalse(module_exists($app->getModuleName()));
        }
    }

    /**
     * Disables apps based on their machine name.
     *
     * @param string[] $machine_names
     *   Machine names of the apps that need to be disabled.
     */
    public function disableAppsByMachineNames($machine_names)
    {
        $this->drupalService->bootstrap($this->webdriver);

        foreach ($machine_names as $key => $machine_name) {
            if (!module_exists($machine_name)) {
                unset($machine_names[$key]);
            }
        }
        if (!count($machine_names)) {
            // Nothing left to do if the apps are already disabled.
            return;
        }

        // Uninstall the app. This doesn't happen during cron but immediately.
        foreach ($machine_names as $machine_name) {
          paddle_apps_deactivate_paddlet($machine_name);
        }
    }

    /**
     * Runs the cron for the paddle apps status changes.
     *
     * @param AppInterface $app
     *   The app that should be installed.
     */
    public function processAppQueue(AppInterface $app)
    {
        if ($this->webdriver->getEnableAppsViaCron() == true) {
            $this->waitForCronToEnableAppsOrHitTimeout($app);
        } else {
            // Paddle apps cron will enable / disable queued apps.
            paddle_apps_cron();
        }
    }

    /**
     * Keep the WebDriver busy until cron run completed installing the app or
     * the server timeout was hit.
     *
     * @param AppInterface $app
     *   The app that should be installed.
     * @return void
     */
    public function waitForCronToEnableAppsOrHitTimeout(AppInterface $app)
    {
        // The time needed depends on:
        // - the app enabling code that needs to run
        // - how frequent the related cron task gets called
        // - the available resources on the machine running the test
        // We want the timeout to be as small as possible to make tests fail as
        // soon as possible if enabling an app fails on a non-time related
        // issue.
        // This timeout was increased little by little each time it proved not
        // to suffice.
        $timeout = 600;
        $start = time();
        while ((time() - $start) <= $timeout) {
            // Quit waiting if the app got installed.
            // We cannot rely on module_exists() because module_enable()
            // sets the status to 1 before the actual enabling code is
            // executed.
            // @see module_enable()
            // In a single process execution this is kind of ok because
            // module_enable() fully gets executed before module_exists() is
            // called. But with cron in the background, we have separate
            // processes. So we rely on the app not being in the Paddle Apps
            // queue any more.
            // We can not rely on paddle_apps_queue_get()->numberOfItems()
            // because that won't be zero if a failing queue item from another
            // app is stuck.
            if (!$this->appIsInQueue($app)) {
                // The module got enabled in separate PHP process by cron.
                // Do some of the steps from module_enable() as if it would
                // have done that in this PHP process.
                // @see DrupalWebTestCase::resetAll()

                // Reset all static variables.
                drupal_static_reset();
                // Explicitly load all module files, including the ones from
                // the newly enabled module(s).
                module_load_all();
                // Refresh the list of hook implementations.
                module_implements('', false, true);
                // Refresh the database schema information. This must be done
                // before drupal_flush_all_caches() so rebuilds can make use of
                // the schema of modules enabled in the separate thread.
                drupal_get_schema(null, true);
                // Perform rebuilds and flush remaining caches.
                drupal_flush_all_caches();

                // Reload variables that can be retrieved via variable_get()
                // because they could've been changed by a separate thread.
                global $conf;
                // Reload variables from the settings file.
                // @see drupal_settings_initialize()
                $conf = array();
                // Make conf_path() available as local variable in settings.php.
                $conf_path = conf_path();
                if (file_exists(DRUPAL_ROOT . '/' . $conf_path . '/settings.php')) {
                    // Use include instead of include_once because we
                    // explicitly want to include it again.
                    include DRUPAL_ROOT . '/' . $conf_path . '/settings.php';
                }
                // Variables cache got cleared by drupal_flush_all_caches(). We
                // don't need to repeat that action. Reload variables from the
                // variables database table without overriding the ones loaded
                // from the settings file.
                // @see DrupalWebTestCase::refreshVariables()
                $conf = variable_initialize($conf);

                return;
            }
            // Ask for something that should be available on every page to
            // revive the browser.
            $this->webdriver->byCssSelector('body');
            // Wait 10 seconds before repeating.
            sleep(10);
        }

        // Server timeout was hit.
        $this->webdriver->fail("App installation via cron didn't succeed within $timeout seconds.");
    }

    /**
     * Is the given app in the queue?
     *
     * A Paddle Apps queue item only gets deleted when it's fully processed.
     * @see paddle_apps_cron()
     *
     * @param AppInterface $app
     *   The app that could be in the queue.
     * @return bool
     */
    public function appIsInQueue(AppInterface $app)
    {
        // We can not use getItems() on a Drupal queue because
        // DrupalQueueInterface doesn't have such method. We rely on the fact
        // that Paddle Apps uses the default Drupal queue backend and directly
        // query it.
        // @see SystemQueue::claimItem()
        /* @var \SelectQuery $query */
        $query = db_select('queue', 'q')
          ->condition('q.name', 'paddle_apps')
          ->condition('q.data', '%"' . db_like($app->getModuleName()) . '"%', 'LIKE');
        return (bool) $query->countQuery()->execute()->fetchField();
    }
}
