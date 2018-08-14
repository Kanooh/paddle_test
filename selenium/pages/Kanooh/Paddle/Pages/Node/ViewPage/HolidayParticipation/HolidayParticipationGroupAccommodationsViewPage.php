<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\HolidayParticipation.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage\HolidayParticipation;

/**
 * The class representing the group accommodations view page.
 */
class HolidayParticipationGroupAccommodationsViewPage extends HolidayParticipationOverViewPage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'aanbod/groepsverblijven';

    /**
     * {@inheritdoc}
     */
    public function waitUntilPageIsLoaded()
    {
        $this->webdriver->waitUntilElementIsDisplayed('//div[contains(@class, "view-display-id-group_accommodations")]');
    }
}
