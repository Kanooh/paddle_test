<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Regression\LanguageSwitcherNotShownTest.
 */

namespace Kanooh\Paddle\Core\Regression;

use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPage;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage\MenuOverviewPage;
use Kanooh\Paddle\Pages\Admin\Structure\TaxonomyManager\OverviewPage\OverviewPage as TaxonomyOverviewPage;
use Kanooh\Paddle\Pages\AdminPage;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests that language switcher remains hidden even when the Multilingual
 * paddlet is not installed.
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 *
 * @see https://one-agency.atlassian.net/browse/KANWEBS-4187
 */
class LanguageSwitcherNotShownTest extends WebDriverTestCase
{
    /**
     * Tests the pages on which the language switcher is present/absent.
     */
    public function testLanguageSwitcherNotPresent()
    {
        $drupal_service = new DrupalService();
        $drupal_service->bootstrap($this);
        if (module_exists('paddle_i18n')) {
            $this->markTestSkipped('This test should run only if paddle i18n was never enabled.');
        }
        // Login first.
        $user_session_service = new UserSessionService($this);
        $user_session_service->login('ChiefEditor');

        // Check that the language switcher is not present on these pages. It
        // was appearing on the Multilingual epic branch without the paddlet
        // being enabled.
        $pages = array(
            'menuOverview' => new MenuOverviewPage($this),
            'contentManager' => new SearchPage($this),
            'taxonomyOverview' => new TaxonomyOverviewPage($this),
        );
        foreach ($pages as $page) {
            /** @var AdminPage $page */
            $page->go();
            $this->assertNull($page->languageSwitcher);
        }
    }
}
