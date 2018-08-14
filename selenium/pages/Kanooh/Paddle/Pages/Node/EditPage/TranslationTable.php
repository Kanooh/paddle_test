<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\TranslationTable.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage;

use \Kanooh\Paddle\Pages\Element\Table\Table;

/**
 * Class representing the translation table on the node edit page.
 * @package Kanooh\Paddle\Pages\Node\EditPage
 */
class TranslationTable extends Table
{

    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//div[contains(@class,"pane-node-translations-overview")]//table';

    /**
     * Finds table <tr>s by their language code and returns their Row object.
     *
     * @param string $language_code
     *   The language code for which to find the row.
     *
     * @return TranslationTableRow
     *   The <tr> we are looking for.
     */
    public function getRowByLanguage($language_code)
    {
        $row_xpath = $this->xpathSelector . '//tr[@data-language="' . $language_code . '"]';
        return new TranslationTableRow($this->webdriver, $row_xpath);
    }
}
