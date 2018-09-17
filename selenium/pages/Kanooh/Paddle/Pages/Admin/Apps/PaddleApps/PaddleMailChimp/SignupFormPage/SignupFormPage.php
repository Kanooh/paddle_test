<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMailChimp\SignupFormPage\SignupFormPage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMailChimp\SignupFormPage;

use Kanooh\Paddle\Pages\PaddlePage;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The the Signup Form entity page.
 *
 * @property SignupFormForm $signupFormForm
 *   The main form on the page.
 * @property SignupFormPageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 */
class SignupFormPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = '';

    /**
     * Constructor for the SignupFormPage class.
     *
     * @param $webdriver
     *   The Selenium web driver test case.
     * @param $path
     *   The value of the class property with the same name.
     */
    public function __construct(WebDriverTestCase $webdriver, $path)
    {
        $this->webdriver = $webdriver;
        // The same page class is used for both entity add and entity edit page
        // so we need to set up the path like that.
        $this->path = $path;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'signupFormForm':
                return new SignupFormForm($this->webdriver, $this->webdriver->byId('mailchimp-signup-form'));
            case 'contextualToolbar':
                return new SignupFormPageContextualToolbar($this->webdriver);
        }
        return parent::__get($property);
    }
}
