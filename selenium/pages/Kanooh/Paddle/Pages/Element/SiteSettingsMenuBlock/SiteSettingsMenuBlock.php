<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\SiteSettingsMenuBlock\SiteSettingsMenuBlock.
 */

namespace Kanooh\Paddle\Pages\Element\SiteSettingsMenuBlock;

use Kanooh\WebDriver\WebdriverTestCase;

/**
 * Class representing the Site Settings Menu block on the top of the page.
 */
class SiteSettingsMenuBlock
{
    /**
     * The links in the Site Settings menu.
     *
     * @var SiteSettingsMenuBlockLinks
     */
    public $links;

    /**
     * Constructs a new SocialIdentitiesTable.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        $this->webdriver = $webdriver;
        $this->links = new SiteSettingsMenuBlockLinks($webdriver);
    }
}
