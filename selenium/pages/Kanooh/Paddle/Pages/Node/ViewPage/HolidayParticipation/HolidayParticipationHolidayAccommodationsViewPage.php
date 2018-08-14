<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\HolidayParticipation.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage\HolidayParticipation;

/**
 * The class representing the holiday accommodations view page.
 */
class HolidayParticipationHolidayAccommodationsViewPage extends HolidayParticipationOverViewPage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'aanbod/vakantieverblijven';

    /**
     * {@inheritdoc}
     */
    public function waitUntilPageIsLoaded()
    {
        $this->webdriver->waitUntilElementIsDisplayed('//div[contains(@class, "view-display-id-holiday_accommodations")]');
    }
}
