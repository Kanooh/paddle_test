<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Datepicker\DatepickerPopup.
 */

namespace Kanooh\Paddle\Pages\Element\Datepicker;

use Kanooh\Paddle\Pages\Element\Element;

/**
 * Base class for a Panels layout.
 */
class DatepickerPopup extends Element
{
    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//div[@id="ui-datepicker-div"]';

    /**
     * The XPath selector that identifies the month select box.
     * @var string
     */
    protected $xpathMonthSelectBoxSelector = '//select[contains(concat(" ", normalize-space(@class), " "), " ui-datepicker-month ")]';

    /**
     * The XPath selector that identifies the year select box.
     * @var string
     */
    protected $xpathYearSelectBoxSelector = '//select[contains(concat(" ", normalize-space(@class), " "), " ui-datepicker-year ")]';

    /**
     * Returns whether or not we have a select box for the month.
     * @return bool
     */
    public function hasMonthSelectBox()
    {
        $element = $this->webdriver->waitUntilElementIsDisplayed($this->xpathMonthSelectBoxSelector);
        return !empty($element);
    }

    /**
     * Returns whether or not we have a select box for the year.
     * @return bool
     */
    public function hasYearSelectBox()
    {
        $element = $this->webdriver->waitUntilElementIsDisplayed($this->xpathYearSelectBoxSelector);
        return !empty($element);
    }
}
