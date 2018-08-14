<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPageCreatedMaxDatepicker.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage;

use Kanooh\Paddle\Pages\Element\Datepicker\Datepicker;

class SearchPageCreatedMaxDatepicker extends Datepicker
{
    /**
     * {@inheritdoc}
     */
    protected $xpathInputFieldSelector = '//input[@name="created[max]"]';
}
