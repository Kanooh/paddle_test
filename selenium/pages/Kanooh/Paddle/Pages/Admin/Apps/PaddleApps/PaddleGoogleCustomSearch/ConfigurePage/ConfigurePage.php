<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleGoogleCustomSearch\ConfigurePage\ConfigurePage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleGoogleCustomSearch\ConfigurePage;

use Kanooh\Paddle\Apps\GoogleCustomSearch;
use Kanooh\Paddle\Pages\Admin\Apps\ConfigurePage\ConfigurePageBase;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The configuration page for the Google Custom Search paddlet.
 *
 * @property Text $cseID
 *   The form element representing the cse ID text field.
 * @property Text $apiKey
 *   The form element representing the API key textfield.
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
        parent::__construct($webdriver, new GoogleCustomSearch);
    }

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'cseID':
                return new Text($this->webdriver, $this->webdriver->byName('cse_id'));
            case 'apiKey':
                return new Text($this->webdriver, $this->webdriver->byName('cse_api_key'));
        }
        return parent::__get($property);
    }
}
