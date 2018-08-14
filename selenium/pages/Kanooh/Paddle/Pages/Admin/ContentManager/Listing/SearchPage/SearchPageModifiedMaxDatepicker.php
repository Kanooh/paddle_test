<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPageModifiedMaxDatepicker.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage;

use Kanooh\Paddle\Pages\Element\Datepicker\Datepicker;

class SearchPageModifiedMaxDatepicker extends Datepicker
{
    /**
     * {@inheritdoc}
     */
    protected $xpathInputFieldSelector = '//input[@name="changed[max]"]';
}
