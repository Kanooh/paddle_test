<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\SiteSettingsMenuBlock\SiteSettingsMenuBlockLinks.
 */

namespace Kanooh\Paddle\Pages\Element\SiteSettingsMenuBlock;

use Kanooh\Paddle\Pages\Element\Links\Links;

/**
 * Class representing the links in the Site Settings Menu block.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkSiteSettings
 *   A link to the site settings page.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkSiteName
 *   A link to the front page.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkHelpDesk
 *   A link to the Paddle Help Desk.
 */
class SiteSettingsMenuBlockLinks extends Links
{

    protected $xpathSelector = '//div[contains(@class, "site-settings-menu")]//ul[contains(@class, "links")]';

    /**
     * {@inheritdoc}
     */
    public function linkInfo()
    {
        return array(
            'SiteSettings' => array(
                'xpath' => $this->xpathSelector . '//a[contains(@class, "site-settings-wrench")]',
            ),
            'SiteName' => array(
                'xpath' => $this->xpathSelector . '//a[contains(@class, "site-settings-name")]',
            ),
            'HelpDesk' => array(
                'xpath' => $this->xpathSelector . '//a[contains(@class, "site-settings-help")]',
            ),
        );
    }
}
