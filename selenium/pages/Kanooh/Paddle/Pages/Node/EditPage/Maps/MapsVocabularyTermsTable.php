<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\Maps\MapsVocabularyTermsTable.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\Maps;

use Kanooh\Paddle\Pages\Element\Table\Table;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class representing the maps vocabulary filter table in the maps page node edit.
 *
 * @property MapsVocabularyTermsTableRow[] $rows
 */
class MapsVocabularyTermsTable extends Table
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
                    $row = new MapsVocabularyTermsTableRow($this->webdriver, $element);
                    $rows[$row->termId] = $row;
                }
                return $rows;
        }

        throw new \RuntimeException("The property with the name $name is not defined.");
    }
}
