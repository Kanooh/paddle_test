<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Listing\ArchivePage\ArchivePageContentTableRow.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Listing\ArchivePage;

use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Represents a table row on the Archive Content page.
 *
 * @property Checkbox $bulkActionCheckbox
 * @property string $title
 * @property ArchivePageContentTableRowLinks $links
 */
class ArchivePageContentTableRow extends Row
{

    /**
     * Constructs an ArchivePageContentTableRow object.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param string $xpath_selector
     *   The XPath selector for this table row.
     */
    public function __construct(WebDriverTestCase $webdriver, $xpath_selector)
    {
        parent::__construct($webdriver);

        $this->xpathSelector = $xpath_selector;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'bulkActionCheckbox':
                $xpath = $this->xpathSelector . '//input[contains(@class, "vbo-select")]';
                return new Checkbox($this->webdriver, $this->getWebdriverElement()->byXPath($xpath));
            case 'title':
                $title = $this->getWebdriverElement()->byXPath($this->xpathSelector . '//td[contains(@class, "views-field-title")]');
                return $title->text();
            case 'links':
                return new ArchivePageContentTableRowLinks($this->webdriver, $this->xpathSelector);
        }

        throw new \Exception("The property with the name $name is not defined.");
    }
}
