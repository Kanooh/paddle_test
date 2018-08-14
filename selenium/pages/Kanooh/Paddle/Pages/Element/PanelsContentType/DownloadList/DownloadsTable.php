<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\DownloadList\DownloadsTable.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\DownloadList;

use Kanooh\Paddle\Pages\Element\Table\SortableTable;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class representing the downloadable atoms in a download list pane form.
 *
 * @package Kanooh\Paddle\Pages\Element\PanelsContentType\DownloadList
 *
 * @property DownloadsTableRow[] $rows
 *   Array of rows available in the table.
 */
class DownloadsTable extends SortableTable
{
    /**
     * The Webdriver element for the table instance.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//table[contains(@id, "paddle-scald-draggable-atoms")]';

    /**
     * @inheritDoc
     */
    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        parent::__construct($webdriver);

        $this->element = $element;
    }

    /**
     * {@inheritDoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'rows':
                $xpath = './/td[not(contains(@class, "empty"))]/..';
                $criteria = $this->element->using('xpath')->value($xpath);
                $elements = $this->element->elements($criteria);

                $rows = array();
                foreach ($elements as $element) {
                    $rows[] = new DownloadsTableRow($this->webdriver, $element);
                }

                return $rows;
        }

        throw new \Exception("The property $name is undefined.");
    }
}
