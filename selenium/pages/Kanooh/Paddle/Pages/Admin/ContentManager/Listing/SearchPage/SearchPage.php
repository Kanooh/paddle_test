<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPage.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage;

use Kanooh\Paddle\Pages\Element\Form\Select;
use Kanooh\Paddle\Pages\Element\Links\ContentAdminMenuLinks;
use Kanooh\Paddle\Pages\AdminPage;

/**
 * Class SearchPage
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $advancedOptions
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $applyButton
 * @property ContentAdminMenuLinks $adminContentLinks
 * @property SearchPageBulkActions $bulkActions
 * @property SearchPageContentTable $contentTable
 * @property SearchPageCreatedMinDatepicker $createdMinDatepicker
 * @property SearchPageCreatedMinDatepicker $createdMaxDatepicker
 * @property SearchPageModifiedMinDatepicker $modifiedMinDatepicker
 * @property SearchPageModifiedMinDatepicker $modifiedMaxDatepicker
 * @property Select $hasNoTranslation
 * @property Select $hasOnlineVersionSelect
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element[] $referencedNodesWarningLinks
 */
class SearchPage extends AdminPage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/content_manager/list/search';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'adminContentLinks':
                return new ContentAdminMenuLinks($this->webdriver);
            case 'advancedOptions':
                return $this->webdriver->byXpath('//div[@id="edit-secondary-wrapper"]//fieldset[@id="edit-secondary"]//a[@class="fieldset-title"]');
            case 'applyButton':
                return $this->webdriver->byXPath('//form[@id="views-exposed-form-content-manager-page"]//input[@id="edit-submit-content-manager"]');
            case 'bulkActions':
                return new SearchPageBulkActions($this->webdriver, $this->webdriver->byId('views-form-content-manager-page'));
            case 'contentTable':
                return new SearchPageContentTable($this->webdriver);
            case 'createdMaxDatepicker':
                return new SearchPageCreatedMaxDatepicker($this->webdriver);
            case 'createdMinDatepicker':
                return new SearchPageCreatedMinDatepicker($this->webdriver);
            case 'hasOnlineVersionSelect':
                return new Select($this->webdriver, $this->webdriver->byId('edit-status'));
            case 'modifiedMaxDatepicker':
                return new SearchPageModifiedMaxDatepicker($this->webdriver);
            case 'modifiedMinDatepicker':
                return new SearchPageModifiedMinDatepicker($this->webdriver);
            case 'hasNoTranslation':
                return new Select($this->webdriver, $this->webdriver->byId('paddle_i18n_missing_target_language'));
            case 'referencedNodesWarningLinks':
                $xpath = '//ul[contains(@class, "referenced-nodes-warning")]//a';
                $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
                return $elements;
        }
        return parent::__get($property);
    }

    /**
     * {@inheritdoc}
     */
    public function waitUntilPageIsLoaded()
    {
        $this->webdriver->waitUntilElementIsDisplayed(
            '//body[contains(@class, "page-admin-content-manager-list-search")]'
        );
    }
}
