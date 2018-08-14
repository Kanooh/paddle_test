<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleSocialMedia\ConfigurePage\ConfigurePage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleSocialMedia\ConfigurePage;

use Kanooh\Paddle\Apps\SocialMedia;
use Kanooh\Paddle\Pages\Admin\Apps\ConfigurePage\ConfigurePageBase;
use Kanooh\Paddle\Pages\PaddlePage;
use Kanooh\Paddle\Pages\Element\Form;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The configuration page for the Social Media paddlet.
 *
 * @property ConfigurePageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property ConfigureForm $configureForm
 *   The main form to configure the paddlet.
 */
class ConfigurePage extends ConfigurePageBase
{
    /**
     * Constructs a ConfigurePage.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The interface to the Selenium webdriver.
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        parent::__construct($webdriver, new SocialMedia);
    }

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'configureForm':
                return new ConfigureForm($this->webdriver, $this->webdriver->byId('paddle-social-media-settings-form'));
        }
        return parent::__get($property);
    }
}
