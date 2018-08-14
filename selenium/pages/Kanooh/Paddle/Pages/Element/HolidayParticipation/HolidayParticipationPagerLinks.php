<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\HolidayParticipation\HolidayParticipationPagerLinks.
 */

namespace Kanooh\Paddle\Pages\Element\HolidayParticipation;

use Kanooh\Paddle\Pages\Element\Links\Links;

/**
 * Class representing the pager links.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkPrevious
 *   The link to go to the previous page.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkNext
 *   The link to go to the next page.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkPageOne
 *   The link to go to page 1.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $linkPageTwo
 *   The link to go to page 2.
 */
class HolidayParticipationPagerLinks extends Links
{
    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//div[contains(@class, "view-id-holiday_participation_filter_pages")]//ul[@class="pager"]';

    /**
     * {@inheritdoc}
     */
    public function linkInfo()
    {
        return array(
            'Previous' => array('xpath' => $this->xpathSelector . '//li[contains(@class, "pager-previous")]/a'),
            'Next' => array('xpath' => $this->xpathSelector . '//li[contains(@class, "pager-next")]/a'),
            'PageOne' => array('xpath' => $this->xpathSelector . '//li[contains(@class, "pager-item")]/a[@title="Go to page 1"]'),
            'PageTwo' => array('xpath' => $this->xpathSelector . '//li[contains(@class, "pager-item")]/a[@title="Go to page 2"]'),
        );
    }
}
