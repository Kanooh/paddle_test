<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\AdvancedSearch\VocabularyTermsTable.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\AdvancedSearch;

use Kanooh\Paddle\Pages\Element\Table\Table;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class representing the vocabulary filter table in the advanced search page node edit.
 *
 * @property VocabularyTermsTableRow[] $rows
 */
class VocabularyTermsTable extends Table
{
    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver, $xpath)
    {
        parent::__construct($webdriver);
        $this->xpathSelector = $xpath;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'rows':
                $rows = array();
                $xpath = $this->xpathSelector . '/tbody/tr';
                $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
                foreach ($elements as $element) {
                    $row = new VocabularyTermsTableRow($this->webdriver, $element);
                    $rows[$row->termId] = $row;
                }
                return $rows;
        }

        throw new \RuntimeException("The property with the name $name is not defined.");
    }
}
