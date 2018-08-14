<?php

/**
 * @file
 * Contains \Kanooh\Paddle\InfoLinksTest.
 */

namespace Kanooh\Paddle\Core;

use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Traits\DataProvider\ThemeDataProviderTrait;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the info links on the user login and forgot password pages.
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class AccessibilityTest extends WebDriverTestCase
{

    /**
     * Use the theme data provider.
     */
    use ThemeDataProviderTrait;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The frontend node view page.
     *
     * @var FrontPage
     */
    protected $frontPage;

    /**
     * @var ThemerOverviewPage
     */
    protected $themerOverviewPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();
        $this->userSessionService = new UserSessionService($this);
        $this->frontPage = new FrontPage($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->themerOverviewPage = new ThemerOverviewPage($this);

        $this->userSessionService->login('SiteManager');
    }

    /**
     * Tests the skip to main content link.
     *
     * @group accessibility
     *
     * @dataProvider themeDataProvider
     *
     * @param string $theme_name
     *   The name of the theme to test.
     * @param null|array $optional_modules
     *   An array of additional themes to enable. Defaults to none.
     */
    public function testSkipToHeaderLink($theme_name, $optional_modules = null)
    {
        // Enable modules if needed.
        if (is_array($optional_modules)) {
            foreach ($optional_modules as $key => $module) {
                if (module_exists($module)) {
                    unset($optional_modules[$key]);
                }
            }
            if (!empty($optional_modules)) {
                module_enable($optional_modules);
                drupal_flush_all_caches();
            }
        }

        // Preview the wanted theme.
        $this->themerOverviewPage->go();
        $this->themerOverviewPage->theme($theme_name)->preview->click();
        $this->frontPage->checkArrival();

        // Assert that the skip link exists.
        $skip_link = $this->byLinkText(t('Skip to main content'));
        // But that it is not displayed on the page.
        $this->assertEquals('visuallyhidden focusable', $skip_link->attribute('class'));
        // It did not work to use $this->assertFalse($skip_link->displayed());
        // Probably because that checks for 'display:none' or
        // 'visibility:hidden'.
    }
}
