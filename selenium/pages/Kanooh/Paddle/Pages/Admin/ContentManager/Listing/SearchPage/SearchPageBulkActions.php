<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\BulkActions.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage;

use Kanooh\Paddle\Pages\Element\BulkActions\BulkActions;
use Kanooh\Paddle\Pages\Element\Form\Select;

/**
 * Class representing the bulk actions for the content manager.
 *
 * @property Select $selectState
 *   The dropdown to select a moderation state.
 * @property Select $selectAssignee
 *   The select to select an assignee.
 * @property Select $selectResponsibleAuthor
 *   The select to select a responsible author.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonNext
 *   The confirm button on the config step of the form.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonConfigCancel
 *   The cancel button on the config step of the form.
 */
class SearchPageBulkActions extends BulkActions
{

    /**
     * @inheritdoc.
     */
    public function __get($property)
    {
        switch ($property) {
            case 'selectState':
                return new Select($this->webdriver, $this->element->byName('bulk_state'));
            case 'buttonConfigCancel':
                return $this->webdriver->byXPath('//a/span[text()="Cancel"]');
            case 'buttonNext':
                return $this->webdriver->byXPath('//input[@value="Next"]');
            case 'selectAssignee':
                return new Select($this->webdriver, $this->element->byName('assignee'));
            case 'selectResponsibleAuthor':
                return new Select($this->webdriver, $this->element->byName('responsible_author'));
        }

        return parent::__get($property);
    }
}
