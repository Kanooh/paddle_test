<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Listing\ArchivePage\ArchivePageBulkActions.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Listing\ArchivePage;

use Kanooh\Paddle\Pages\Element\BulkActions\BulkActions;
use Kanooh\Paddle\Pages\Element\Form\Checkbox;

/**
 * Class representing the bulk actions for the archive view.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonNext
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonConfigCancel
 * @property Checkbox $selectAll
 */
class ArchivePageBulkActions extends BulkActions
{

    /**
     * @inheritdoc.
     */
    public function __get($property)
    {
        switch ($property) {
            case 'buttonConfigCancel':
                return $this->webdriver->byXPath('//a/span[text()="Cancel"]');
            case 'buttonNext':
                return $this->webdriver->byXPath('//input[@value="Next"]');
            case 'selectAll':
                return new Checkbox($this->webdriver, $this->element->byCssSelector('.vbo-table-select-all'));
        }

        return parent::__get($property);
    }
}
