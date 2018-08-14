<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\AdvancedSearch\AdvancedSearchPage.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\AdvancedSearch;

use Kanooh\Paddle\Pages\Node\EditPage\EditPage;

/**
 * Page to edit an advanced search page.
 *
 * @property AdvancedSearchForm $advancedSearchForm
 */
class AdvancedSearchPage extends EditPage
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'advancedSearchForm':
                return new AdvancedSearchForm(
                    $this->webdriver,
                    $this->webdriver->byClassName('node-paddle_advanced_search_page-form')
                );
        }

        return parent::__get($property);
    }
}
