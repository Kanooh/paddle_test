<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\ConfigurePage\ConfigurePageBase.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\ConfigurePage;

use Kanooh\Paddle\Apps\AppInterface;
use Kanooh\Paddle\Pages\PaddlePage;
use Kanooh\WebDriver\WebDriverTestCase;
use Kanooh\Paddle\Pages\Element\LanguageSwitcher\LanguageSwitcher;


/**
 * The configuration page for the paddlets.
 *
 * @property ConfigurePageContextualToolbarBase $contextualToolbar
 * @property LanguageSwitcher|null $languageSwitcher
 */
abstract class ConfigurePageBase extends PaddlePage
{
    /**
     * Constructs a ConfigurePage.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     *   The interface to the Selenium webdriver.
     * @param AppInterface $app
     *   The app that is being configured.
     */
    public function __construct(WebDriverTestCase $webdriver, AppInterface $app)
    {
        parent::__construct($webdriver);

        $this->path = 'admin/paddlet_store/app/' . $app->getModuleName() . '/configure';
    }

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new ConfigurePageContextualToolbarBase($this->webdriver);
            case 'languageSwitcher':
                try {
                    // Pass the container as it is uncertain if the language
                    // switcher is an <ul> or <select>.
                    $container = $this->webdriver->byId('block-locale-language-content');
                    return new LanguageSwitcher($this->webdriver, $container);
                } catch (\Exception $e) {
                    return null;
                }
        }
        return parent::__get($property);
    }
}
