<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\HolidayParticipation\HolidayParticipationTopCategoryLinks.
 */

namespace Kanooh\Paddle\Pages\Element\HolidayParticipation;

use Kanooh\Paddle\Pages\Element\Links\Links;

/**
 * Class representing the views top category links.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkDayTrips
 *   The link to Day trips view page.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkOrganisedHolidays
 *   The link to Organised holidays view page.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkHolidayAccommodations
 *   The link to Holiday accommodations view page.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkGroupAccommodations
 *   The link to Day Group accommodations page.
 */
class HolidayParticipationTopCategoryLinks extends Links
{
    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//div[contains(@class, "view-id-holiday_participation_filter_pages")]//div[contains(@class, "view-header")]//div[contains(@class, "hp-categories-links")]';

    /**
     * {@inheritdoc}
     */
    public function linkInfo()
    {
        return array(
        'DayTrips' => array('xpath' => $this->xpathSelector . '//a[@id="hp-day_trips-link"]'),
        'OrganisedHolidays' => array('xpath' => $this->xpathSelector . '//a[@id="hp-organised_holidays-link"]'),
        'HolidayAccommodations' => array('xpath' => $this->xpathSelector . '//a[@id="hp-holiday_accommodations-link"]'),
        'GroupAccommodations' => array('xpath' => $this->xpathSelector . '//a[@id="hp-group_accommodations-link"]'),
        );
    }
}
