<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPageModifiedMinDatepicker.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage;

use Kanooh\Paddle\Pages\Element\Datepicker\Datepicker;

class SearchPageModifiedMinDatepicker extends Datepicker
{
    /**
     * {@inheritdoc}
     */
    protected $xpathInputFieldSelector = '//input[@name="changed[min]"]';
}
