<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPageContentTableRow.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage;

use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Represents a table row on the Search Content page.
 */
class SearchPageContentTableRow extends Row
{
    /**
     * The action links on the table row.
     *
     * @var SearchPageContentTableRowLinks
     */
    public $links;

    /**
     * The checkbox to select the row for bulk actions.
     *
     * @var Checkbox
     */
    public $bulkActionCheckbox;

    /**
     * Constructs an SearchPageContentTableRow object.
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
        $this->links = new SearchPageContentTableRowLinks($this->webdriver, $xpath_selector);
        $this->bulkActionCheckbox = $this->getBulkActionCheckbox();
    }

    /**
     * Get the bulk action checkbox if there is one, false otherwise.
     */
    public function getBulkActionCheckbox()
    {
        $xpath = $this->xpathSelector . '//input[contains(@class, "vbo-select")]';
        $criteria = $this->webdriver->using('xpath')->value($xpath);
        $elements = $this->webdriver->elements($criteria);
        if (count($elements) > 0) {
            return new Checkbox($this->webdriver, $elements[0]);
        }

        return false;
    }

    /**
     * Checks if a given row contains the correct status for the node.
     *
     * @param string $status
     *   The status that should be shown in the row.
     *
     * @return bool
     *   True if the row contains the given status, false otherwise.
     */
    public function checkStatus($status)
    {
        $criteria = $this->webdriver->using('xpath')->value($this->xpathSelector . '//td[contains(@class, "views-field-state") and contains(concat(" ", text(), " "), " ' . $status . ' ")]/..');
        $elements = $this->webdriver->elements($criteria);

        return (bool)count($elements);
    }

    /**
     * Retrieves the status label for this row.
     *
     * @return string
     *   The status label.
     */
    public function getStatus()
    {
        $xpath = $this->xpathSelector . '//td[contains(@class, "views-field-state")]';

        return trim($this->webdriver->byXPath($xpath)->text());
    }

    /**
     * Checks if a node is translated in the defined language.
     *
     * @param string $lang_code
     *   The language code of the defined language.
     *
     * @return bool
     *   Whether the node exists in the defined language.
     */
    public function isTranslatedInLanguage($lang_code)
    {
        $criteria = $this->webdriver->using('xpath')->value($this->xpathSelector . '//td[contains(@class, "views-field-translations")]/span[contains(@class, "locale-translated")]/a/span[contains(concat(" ", text(), " "), " ' . $lang_code . ' ")]');
        $elements = $this->webdriver->elements($criteria);

        return (bool)count($elements);
    }

    /**
     * Retrieves the link to the translated node.
     *
     * @param $lang_code
     *   The language code of the defined language.
     *
     * @return string
     *   If a translation link is found, the translation link; otherwise an empty string.
     */
    public function getTranslationLink($lang_code)
    {
        $link = '';

        if ($this->isTranslatedInLanguage($lang_code)) {
            $xpath = $this->xpathSelector . '//td[contains(@class, "views-field-translations")]/span[contains(@class, "locale-translated")]/a/span[contains(concat(" ", text(), " "), " ' . $lang_code . ' ")]/..';

            $link = $this->webdriver->byXPath($xpath)->attribute('href');
        }

        return $link;
    }
}
