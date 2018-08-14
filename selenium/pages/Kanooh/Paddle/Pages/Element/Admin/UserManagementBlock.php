<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Admin\UserManagementBlock.
 */

namespace Kanooh\Paddle\Pages\Element\Admin;

use Kanooh\Paddle\Pages\Element\Element;

/**
 * The user management block.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $userName
 *   The username.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $profilePicture
 *   The profile picture..
 * @property UserLinks $userLinks
 *   The user management links.
 */
class UserManagementBlock extends Element
{

    protected $xpathSelector = "//div[contains(@class, 'personal-info')]";

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'userName':
                return $this->webdriver->byXPath($this->xpathSelector . "//div[contains(@class, 'username')]");
            case 'profilePicture':
                return $this->webdriver->byXPath($this->xpathSelector . "//div[contains(@class, 'user-picture')]//img");
            case 'userLinks':
                return new UserLinks($this->webdriver);
        }

        throw new \Exception("The property $property is undefined.");
    }
}
