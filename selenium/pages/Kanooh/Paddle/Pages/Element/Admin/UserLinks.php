<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Admin\UserLinks.
 */

namespace Kanooh\Paddle\Pages\Element\Admin;

use Kanooh\Paddle\Pages\Element\Links\Links;

/**
 * The user mangement links.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkUser
 *   A link to the "User" page.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkLogout
 *   A link to log out.
 */
class UserLinks extends Links
{

    protected $xpathSelector = '//div[@class="user-links"]';

    /**
     * {@inheritdoc}
     */
    public function linkInfo()
    {
        return array(
            'User' => array(
                'xpath' => $this->xpathSelector . '//li[contains(@class,"user-profile")]/a',
            ),
            'Logout' => array(
                'xpath' => $this->xpathSelector . '//li[contains(@class,"logout")]/a',
            ),
        );
    }
}
