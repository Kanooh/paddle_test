<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\TranslationPage\TranslationPageTable.
 */

namespace Kanooh\Paddle\Pages\Node\TranslatePage;

use Kanooh\Paddle\Pages\Node\EditPage\TranslationTable;

/**
 * Class representing the translation table on the node translation page.
 *
 * @package Kanooh\Paddle\Pages\Node\TranslationPage\TranslationPageTable
 */
class TranslationPageTable extends TranslationTable
{

    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//table[@id="translation-table"]';
}
