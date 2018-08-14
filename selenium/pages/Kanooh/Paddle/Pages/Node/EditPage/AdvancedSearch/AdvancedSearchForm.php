<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\AdvancedSearch\AdvancedSearchForm.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\AdvancedSearch;

use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Form\Checkboxes;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class representing the form portion specific for the advanced search page.
 *
 * @property Checkboxes $contentTypes
 * @property DefaultSortOptionRadios $defaultSortOption
 * @property DefaultSortOrderRadios $defaultSortOrder
 * @property VocabularyTermsTable $vocabularyTermsTable
 * @property Checkbox $enableSearchForm
 * @property Checkbox $useDefaultSearchButtonText
 * @property Text $customSearchButtonText
 * @property Checkbox $filterAuthorsCheckbox
 * @property Checkbox $filterKeywordsCheckbox
 * @property Checkbox $filterPublicationYearCheckbox
 * @property Checkbox $filterActionStrategiesCheckbox
 * @property Checkbox $filterPolicyThemesCheckbox
 * @property Checkbox $filterSettingsCheckbox
 * @property Checkbox $displayResultCount
 * @property Checkbox $pagerTop
 * @property Checkbox $pagerBottom
 */
class AdvancedSearchForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'contentTypes':
                return new Checkboxes(
                    $this->webdriver,
                    $this->webdriver->byClassName('field-name-field-adv-search-content-types')
                );
            case 'defaultSortOption':
                return new DefaultSortOptionRadios(
                    $this->webdriver,
                    $this->webdriver->byId('edit-field-paddle-default-sort-option-und')
                );
            case 'defaultSortOrder':
                return new DefaultSortOrderRadios(
                    $this->webdriver,
                    $this->webdriver->byId('edit-field-paddle-default-sort-order-und')
                );
            case 'vocabularyTermsTable':
                return new VocabularyTermsTable(
                    $this->webdriver,
                    '//div[contains(@class, "pane-vocabulary-filter-field")]//table'
                );
            case 'enableSearchForm':
                return new Checkbox(
                    $this->webdriver,
                    $this->webdriver->byName('search_form[enabled]')
                );
            case 'useDefaultSearchButtonText':
                return new Checkbox(
                    $this->webdriver,
                    $this->webdriver->byName('search_form[use_default_btn_text]')
                );
            case 'customSearchButtonText':
                return new Text(
                    $this->webdriver,
                    $this->webdriver->byName('search_form[custom_btn_text]')
                );
            case 'filterAuthorsCheckbox':
                return new Checkbox(
                    $this->webdriver,
                    $this->webdriver->byName('field_paddle_kce_adv_authors[und]')
                );
            case 'filterKeywordsCheckbox':
                return new Checkbox(
                    $this->webdriver,
                    $this->webdriver->byName('field_paddle_kce_adv_keywords[und]')
                );
            case 'filterPublicationYearCheckbox':
                return new Checkbox(
                    $this->webdriver,
                    $this->webdriver->byName('field_paddle_kce_adv_pub_year[und]')
                );
            case 'filterActionStrategiesCheckbox':
                return new Checkbox(
                    $this->webdriver,
                    $this->webdriver->byName('field_paddle_cirro_adv_strats[und]')
                );
            case 'filterPolicyThemesCheckbox':
                return new Checkbox(
                    $this->webdriver,
                    $this->webdriver->byName('field_paddle_cirro_adv_themes[und]')
                );
            case 'filterSettingsCheckbox':
                return new Checkbox(
                    $this->webdriver,
                    $this->webdriver->byName('field_paddle_cirro_adv_settings[und]')
                );
            case 'displayResultCount':
                return new Checkbox(
                    $this->webdriver,
                    $this->webdriver->byName('field_paddle_search_result_count[und]')
                );
            case 'pagerTop':
                return new Checkbox(
                    $this->webdriver,
                    $this->webdriver->byName('field_paddle_pager_top[und]')
                );
            case 'pagerBottom':
                return new Checkbox(
                    $this->webdriver,
                    $this->webdriver->byName('field_paddle_pager_bottom[und]')
                );
        }

        throw new FormFieldNotDefinedException($name);
    }
}
