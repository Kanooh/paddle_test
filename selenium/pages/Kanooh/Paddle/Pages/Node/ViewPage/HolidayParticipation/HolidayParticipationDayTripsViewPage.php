<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\HolidayParticipation.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage\HolidayParticipation;

/**
 * The class representing the paddle day trips view page.
 */
class HolidayParticipationDayTripsViewPage extends HolidayParticipationOverViewPage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'aanbod/daguitstappen';

    /**
     * {@inheritdoc}
     */
    public function waitUntilPageIsLoaded()
    {
        $this->webdriver->waitUntilElementIsDisplayed('//div[contains(@class, "view-display-id-day_trips")]');
    }
}
