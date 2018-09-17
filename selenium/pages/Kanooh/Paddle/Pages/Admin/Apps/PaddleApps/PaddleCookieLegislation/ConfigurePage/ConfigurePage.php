<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCookieLegislation\ConfigurePage\ConfigurePage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCookieLegislation\ConfigurePage;

use Kanooh\Paddle\Apps\CookieLegislation;
use Kanooh\Paddle\Pages\Admin\Apps\ConfigurePage\ConfigurePageBase;
use Kanooh\Paddle\Pages\Admin\Apps\ConfigurePage\ConfigurePageContextualToolbarBase;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The configuration page for the Cookie Legislation paddlet.
 *
 * @property ConfigureForm $form
 */
class ConfigurePage extends ConfigurePageBase
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/paddlet_store/app/paddle_cookie_legislation/configure';

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        parent::__construct($webdriver, new CookieLegislation);
    }

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'form':
                return new ConfigureForm($this->webdriver, $this->webdriver->byId('paddle-cookie-legislation-settings-form'));
                break;
        }

        return parent::__get($property);
    }
}
