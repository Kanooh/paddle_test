<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Utilities\DrupalService.
 */

namespace Kanooh\Paddle\Utilities;

use Drupal\Component\Utility\Random;
use Drupal\Driver\Cores\Drupal7;
use Drupal\Driver\DrupalDriver;
use Kanooh\WebDriver\WebDriverTestCase;

class DrupalService
{
    private static $bootstrapped = false;

    /**
     * Bootstraps Drupal.
     *
     * @param WebDriverTestCase $testCase
     *   The Selenium web driver.
     *
     * @throws \Exception
     */
    public function bootstrap(WebDriverTestCase $testCase)
    {
        if (self::$bootstrapped) {
            // Bootstrap only once.
            return;
        }

        $annotations = $testCase->getAnnotations();
        if (!array_key_exists('preserveGlobalState', $annotations['class']) || $annotations['class']['preserveGlobalState'][0] != 'disabled') {
            // To prevent this exception, annotate to your test class with:
            // @runTestsInSeparateProcesses
            // @preserveGlobalState disabled
            throw new \Exception('Will not bootstrap Drupal because it will mess with the globals. Ensure tests requiring a bootstrapped Drupal are disabling global state preservation.');
        }

        // Path to Drupal physical root directory.
        $dpl_dir = realpath(__DIR__ . '/../../../../..');
        // Base URL.
        $uri = $testCase->base_url;

        // Bootstrap Drupal.
        $driver = new DrupalDriver($dpl_dir, $uri);
        $driver->core = new Drupal7($dpl_dir, $uri, new Random());
        $driver->bootstrap();

        set_include_path($dpl_dir . PATH_SEPARATOR . get_include_path());

        // Needed for code that does not use DRUPAL_ROOT, but instead relies
        // on Drupal root being the current working directory.
        // Like libraries_get_libraries().
        chdir($dpl_dir);

        self::$bootstrapped = true;
    }

    public function isBootstrapped()
    {
        return true === self::$bootstrapped;
    }
}
