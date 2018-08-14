<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\User\PasswordPage\PasswordPage.
 */
namespace Kanooh\Paddle\Pages\User\PasswordPage;

use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The password page.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkContact
 *   The contact link.
 * @property PasswordForm passwordForm
 *   The password form.
 */
class PasswordPage extends PaddlePage
{

    /**
     * {@inheritdoc}
     */
    protected $path = 'user/password';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'linkContact':
                return $this->webdriver->byXPath('//a[@id="contact-button"]');
            case 'passwordForm':
                return new PasswordForm($this->webdriver, $this->webdriver->byId('user-pass'));
        }
        return parent::__get($property);
    }
}
