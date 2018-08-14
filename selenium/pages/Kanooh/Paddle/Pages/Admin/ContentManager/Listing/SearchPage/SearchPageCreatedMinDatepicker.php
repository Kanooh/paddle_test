<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPageCreatedMinDatepicker.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage;

use Kanooh\Paddle\Pages\Element\Datepicker\Datepicker;

class SearchPageCreatedMinDatepicker extends Datepicker
{
    /**
     * {@inheritdoc}
     */
    protected $xpathInputFieldSelector = '//input[@name="created[min]"]';
}
