<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPageContentTable.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage;

use \Kanooh\Paddle\Pages\Element\Table\Table;

class SearchPageContentTable extends Table
{

    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//div[contains(@class,"view-content-manager")]//table[contains(@class,"views-table")]';

    /**
     * Finds table <tr>s by the node id in them and returns their Row object.
     *
     * @param int $nid
     *   The id of the node. It will be used in the xpath to find the node.
     *
     * @return SearchPageContentTableRow|false
     *   The <tr> we are looking for, false otherwise.
     */
    public function getNodeRowByNid($nid)
    {
        $row_xpath = $this->xpathSelector . '//tr//td[contains(@class,"views-field-nid-1") and contains(concat(" ", text(), " "), " ' . $nid . ' ")]/..';
        $criteria = $this->webdriver->using('xpath')->value($row_xpath);
        $elements = $this->webdriver->elements($criteria);

        if (count($elements)) {
            $this->webdriver->waitUntilElementIsDisplayed($row_xpath);
            return new SearchPageContentTableRow($this->webdriver, $row_xpath, $nid);
        }

        return false;
    }
}
