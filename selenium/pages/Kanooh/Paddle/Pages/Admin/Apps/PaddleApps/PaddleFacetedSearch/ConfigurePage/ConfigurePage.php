<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleFacetedSearch\ConfigurePage\ConfigurePage.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleFacetedSearch\ConfigurePage;

use Kanooh\Paddle\Apps\FacetedSearch;
use Kanooh\Paddle\Pages\Admin\Apps\ConfigurePage\ConfigurePageBase;
use Kanooh\Paddle\Pages\Element\Form\Checkboxes;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The configuration page for the Faceted Search paddlet.
 *
 * @property Checkboxes $facetTermsCheckboxes
 *   The checkbox to enable/disable the content type facet.
 * @property Checkboxes $contentTypesCheckboxes
 *   The checkboxes representing the content types.
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
        parent::__construct($webdriver, new FacetedSearch);
    }

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'facetTermsCheckboxes':
                return new Checkboxes(
                    $this->webdriver,
                    $this->webdriver->byXPath('//div[@id="edit-paddle-faceted-search-facet-terms"]')
                );
            case 'contentTypesCheckboxes':
                return new Checkboxes(
                    $this->webdriver,
                    $this->webdriver->byXPath('//div[@id="edit-paddle-faceted-search-content-types"]')
                );
        }
        return parent::__get($property);
    }

    /**
     * Saves the configuration form.
     */
    public function save()
    {
        $this->checkPath();
        $this->contextualToolbar->buttonSave->click();
        $this->webdriver->waitUntilTextIsPresent('The configuration options have been saved.');
    }
}
