<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\HolidayParticipation\HolidayParticipationMapListSwitcher.
 */

namespace Kanooh\Paddle\Pages\Element\HolidayParticipation;

use Kanooh\Paddle\Pages\Element\Links\Links;

/**
 * Class representing the map/list switcher links.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkList
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkMap
 */
class HolidayParticipationMapListSwitcher extends Links
{
    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//div[contains(@class, "view-id-holiday_participation_filter_pages")]//div[@class="hp-offer-display-switcher"]';

    /**
     * {@inheritdoc}
     */
    public function linkInfo()
    {
        return array(
        'List' => array('xpath' => $this->xpathSelector . '//a[contains(@class, "hp-list-switcher")]'),
        'Map' => array('xpath' => $this->xpathSelector . '//a[contains(@class, "hp-map-switcher")]'),
        );
    }
}
