<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleRedirect\ConfigurePage\ConfigurePage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleRedirect\ConfigurePage;

use Kanooh\Paddle\Apps\Redirect;
use Kanooh\Paddle\Pages\Admin\Apps\ConfigurePage\ConfigurePageBase;
use Kanooh\Paddle\Pages\Element\Redirect\RedirectTable;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The configuration page for the Redirect paddlet.
 *
 * @property ConfigurePageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property RedirectTable $redirectTable
 *   The table of redirects.
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
        parent::__construct($webdriver, new Redirect);
    }

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new ConfigurePageContextualToolbar($this->webdriver);
                break;
            case 'redirectTable':
                return new RedirectTable($this->webdriver, '//table[contains(@class, "redirect-list-table")]');
                break;
        }
        return parent::__get($property);
    }

    /**
     * Checks if the redirect table is present on the page.
     *
     * @return boolean
     *   TRUE if present, FALSE if not.
     */
    public function redirectTablePresent()
    {
        $criteria = $this->webdriver->using('xpath')->value('//table[contains(@class, "redirect-list-table")]');
        $elements = $this->webdriver->elements($criteria);
        if (count($elements) > 0) {
            return true;
        }
        return false;
    }

    /**
     * Check if the filter field is present.
     *
     * @return bool
     *   TRUE if the filter field is present, FALSE otherwise.
     */
    public function checkFilterPresent()
    {
        $xpath = '';
        $criteria = $this->webdriver->using('xpath')->value('//input[@id="edit-filter"]');
        $elements = $this->webdriver->elements($criteria);
        return (bool) count($elements);
    }
}
