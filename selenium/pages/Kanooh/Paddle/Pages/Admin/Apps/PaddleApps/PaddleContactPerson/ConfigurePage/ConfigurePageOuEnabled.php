<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleContactPerson\ConfigurePage\ConfigurePageOuEnabled.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleContactPerson\ConfigurePage;

use Kanooh\Paddle\Apps\ContactPerson;
use Kanooh\Paddle\Pages\Admin\Apps\ConfigurePage\ConfigurePageBase;
use Kanooh\Paddle\Pages\Element\ContactPerson\ContactPersonTable;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The configuration page for the Contact Person paddlet when OU paddlet is enabled.
 *
 * @property ConfigurePageContextualToolbar $contextualToolbar
 * @property ContactPersonTable $contactPersonTable
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $exportCSV
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $exportXLS
 */
class ConfigurePageOuEnabled extends ConfigurePageBase
{

    /**
     * Constructs a ConfigurePage.
     *
     * @param \Kanooh\WebDriver\WebDriverTestCase $webdriver
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        parent::__construct($webdriver, new ContactPerson);
    }

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        $base_xpath = '//div[contains(@class, "view-id-ou_contact_person_overview")]';

        switch ($property) {
            case 'contextualToolbar':
                return new ConfigurePageContextualToolbar($this->webdriver);
            case 'contactPersonTable':
                return new ContactPersonTable($this->webdriver, $base_xpath . '//table');
            case 'exportCSV':
                $xpath = $base_xpath . '//a[contains(@href, "admin/paddlet_store/app/paddle_contact_person/cp-ou-export/export/csv")]';
                return $this->webdriver->element($this->webdriver->using('xpath')->value($xpath));
            case 'exportXLS':
                $xpath = $base_xpath . '//a[contains(@href, "admin/paddlet_store/app/paddle_contact_person/cp-ou-export/export/xls")]';
                return $this->webdriver->element($this->webdriver->using('xpath')->value($xpath));
        }
        return parent::__get($property);
    }
}
