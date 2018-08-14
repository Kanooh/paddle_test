<?php

/**
 * @file
 * Contains Kanooh\Paddle\Core\mobileMenuTest.
 */

namespace Kanooh\Paddle\Core;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage\MenuOverviewPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Traits\DataProvider\ThemeDataProviderTrait;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the mobile menu functionality.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class MobileMenuTest extends WebDriverTestCase
{

    /**
     * Use the theme data provider.
     */
    use ThemeDataProviderTrait;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var FrontPage
     */
    protected $frontPage;

    /**
     * @var MenuOverviewPage
     */
    protected $menuOverviewPage;

    /**
     * @var ThemerOverviewPage
     */
    protected $themerOverviewPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->frontPage = new FrontPage($this);
        $this->menuOverviewPage = new MenuOverviewPage($this);
        $this->themerOverviewPage = new ThemerOverviewPage($this);
        $this->userSessionService = new UserSessionService($this);

        $drupal = new DrupalService();
        $drupal->bootstrap($this);

        // Log in as site manager.
        $this->userSessionService->login('SiteManager');
    }

    /**
     * Tests that the mobile menu trigger button works.
     *
     * @dataProvider themeDataProvider
     *
     * @param string $theme_name
     *   The name of the theme to test.
     * @param bool|array $optional_modules
     *   An array of additional themes to enable. Defaults to none.
     */
    public function testMobileMenuTrigger($theme_name, $optional_modules = null)
    {
        // Create a menu item.
        $this->menuOverviewPage->go();
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $this->menuOverviewPage->createMenuItem(array(
            'title' => $title,
        ));

        // Enable modules if needed.
        if (!empty($optional_modules)) {
            module_enable($optional_modules);
            drupal_flush_all_caches();
        }

        // Preview the wanted theme.
        $this->themerOverviewPage->go();
        $this->themerOverviewPage->theme($theme_name)->preview->click();
        $this->frontPage->checkArrival();

        // Verify that the menu element is there.
        $this->assertNotNull($this->frontPage->mainMenuDisplay->getMenuItemLinkByTitle($title));

        // Verify that the mobile menu trigger is not displayed on normal size.
        $this->assertFalse($this->frontPage->mobileMenuTrigger->displayed());

        // Resize the browser window to show the mobile menu trigger.
        $this->resizeCurrentWindow(500);

        $testcase = $this;
        $callable = new SerializableClosure(
            function () use ($testcase) {
                if ($testcase->frontPage->mobileMenuTrigger->displayed()) {
                    return true;
                }
            }
        );
        $this->waitUntil($callable, $this->getTimeout());

        // Now the main menu should not be visible anymore.
        $this->assertFalse($this->frontPage->mainMenuDisplay->getWebdriverElement()->displayed());

        // Open the mobile menu.
        $this->frontPage->mobileMenuTrigger->click();

        // Wait for the menu to be visible again.
        $callable = new SerializableClosure(
            function () use ($testcase) {
                if ($testcase->frontPage->mainMenuDisplay->getWebdriverElement()->displayed()) {
                    return true;
                }
            }
        );
        $this->waitUntil($callable, $this->getTimeout());

        // Try to click the menu item.
        $this->frontPage->mainMenuDisplay->getMenuItemLinkByTitle($title)->click();
        $this->frontPage->checkArrival();
    }
}
