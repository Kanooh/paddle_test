<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Listing\ArchivePage\ArchivePageContentTable.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Listing\ArchivePage;

use \Kanooh\Paddle\Pages\Element\Table\Table;

/**
 * Class ArchivePageContentTable.
 *
 * @property ArchivePageContentTableRow[] $rows
 */
class ArchivePageContentTable extends Table
{
    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//div[contains(@class, "view-paddle-archive")]//table[contains(@class, "views-table")]';

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'rows':
                $rows = array();
                $xpath = $this->xpathSelector . '/tbody/tr';
                $elements = $this->getWebdriverElement()->elements($this->webdriver->using('xpath')->value($xpath));
                foreach ($elements as $element) {
                    $rows[] = new ArchivePageContentTableRow($this->webdriver, $element);
                }
                return $rows;
                break;
        }
        throw new \Exception("The property with the name $name is not defined.");
    }

    /**
     * Finds table <tr>s by the node id in them and returns their Row object.
     *
     * @param int $nid
     *   The id of the node. It will be used in the xpath to find the node.
     *
     * @return ArchivePageContentTableRow|false
     *   The <tr> we are looking for, false otherwise.
     */
    public function getNodeRowByNid($nid)
    {
        $row_xpath = $this->xpathSelector . '//tr//td[contains(@class, "views-field-nid-1") and contains(concat(" ", text(), " "), " ' . $nid . ' ")]/..';
        $criteria = $this->webdriver->using('xpath')->value($row_xpath);
        $elements = $this->webdriver->elements($criteria);

        if (count($elements)) {
            $this->webdriver->waitUntilElementIsDisplayed($row_xpath);
            return new ArchivePageContentTableRow($this->webdriver, $row_xpath, $nid);
        }

        return false;
    }
}
