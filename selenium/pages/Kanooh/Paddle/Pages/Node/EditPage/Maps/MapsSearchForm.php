<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\Maps\MapsSearchForm.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\Maps;

use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Form\Checkboxes;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class representing the form portion specific for the maps search page.
 *
 * @property Checkboxes $contentTypes
 * @property MapsVocabularyTermsTable $vocabularyTermsTable
 * @property Checkbox $enableSearchForm
 * @property Checkbox $useDefaultSearchButtonText
 * @property Text $customSearchButtonText
 */
class MapsSearchForm extends Form
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
                    $this->webdriver->byClassName('field-name-field-map-content-types')
                );
            case 'vocabularyTermsTable':
                return new MapsVocabularyTermsTable(
                    $this->webdriver,
                    '//div[contains(@class, "pane-map-vocabulary-filter-field")]//table'
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
        }

        throw new FormFieldNotDefinedException($name);
    }
}
