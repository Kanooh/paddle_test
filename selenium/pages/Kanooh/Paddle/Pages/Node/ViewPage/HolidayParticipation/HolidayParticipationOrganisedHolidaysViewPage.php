<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\HolidayParticipation.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage\HolidayParticipation;

/**
 * The class representing the organised holidays view page.
 */
class HolidayParticipationOrganisedHolidaysViewPage extends HolidayParticipationOverViewPage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'aanbod/georganiseerdevakanties';

    /**
     * {@inheritdoc}
     */
    public function waitUntilPageIsLoaded()
    {
        $this->webdriver->waitUntilElementIsDisplayed('//div[contains(@class, "view-display-id-organised_holidays")]');
    }
}
