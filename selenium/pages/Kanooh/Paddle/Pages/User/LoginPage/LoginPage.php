<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\User\LoginPage\LoginPage.
 */
namespace Kanooh\Paddle\Pages\User\LoginPage;

use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The login page.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkContact
 *   The contact link.
 * @property LoginForm loginForm
 *   The login form.
 */
class LoginPage extends PaddlePage
{

    /**
     * {@inheritdoc}
     */
    protected $path = 'user';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'linkContact':
                return $this->webdriver->byXPath('//a[@id="contact-button"]');
            case 'loginForm':
                return new LoginForm($this->webdriver, $this->webdriver->byId('user-login'));
        }
        return parent::__get($property);
    }

    /**
     * {@inheritdoc}
     */
    public function checkPath()
    {
        // This page is also accessible from the 'admin' path.
        $current_path = $this->webdriver->path();
        $current_path = trim($current_path, '/');
        if ($current_path != 'admin/dashboard') {
            parent::checkPath();
        }
    }
}
