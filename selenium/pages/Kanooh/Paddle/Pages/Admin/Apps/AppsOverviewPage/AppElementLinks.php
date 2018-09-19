<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\AppsOverviewPage\AppElementLinks.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\AppsOverviewPage;

use Kanooh\Paddle\Pages\Element\Links\Links;

/**
 * Links for apps that are displayed on the apps overview page.
 *
 * These links are styled as buttons.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkEnable
 *   The link to enable the app.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkConfigure
 *   The link to configure the app.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkInfo
 *   The link to view information about the app.
 */
class AppElementLinks extends Links
{
    /**
     * {@inheritdoc}
     */
    public function linkInfo()
    {
        return array(
            'Configure' => array('xpath' => './/a[contains(@class, "configure")]'),
            'Enable' => array('xpath' => './/a[contains(@class, "enable")]'),
            'Info' => array('xpath' => './/a[contains(@class, "info")]'),
        );
    }
}
