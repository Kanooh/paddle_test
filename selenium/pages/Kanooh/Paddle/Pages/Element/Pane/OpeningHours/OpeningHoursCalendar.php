<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\OpeningHours\OpeningHoursCalendar.
 */

namespace Kanooh\Paddle\Pages\Element\Pane\OpeningHours;

use Kanooh\Paddle\Pages\Element\Pane\Pane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\OpeningHours\OpeningHoursCalendarPanelsContentType;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for a Panels pane with Ctools content type 'Listing'.
 */
class OpeningHoursCalendar extends Pane
{
    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//div[contains(@class, "pane-opening-hours-calendar")]';

    /**
     * @var OpeningHoursCalendarPanelsContentType
     */
    public $contentType;

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver, $uuid, $xpath_selector = '')
    {
        parent::__construct($webdriver, $uuid, $xpath_selector);

        $this->contentType = new OpeningHoursCalendarPanelsContentType($this->webdriver);
    }
}
