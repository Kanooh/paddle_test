<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\HolidayParticipation\HolidayParticipationOverViewPage.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage\HolidayParticipation;

use Kanooh\Paddle\Pages\Element\HolidayParticipation\HolidayParticipationPagerLinks;
use Kanooh\Paddle\Pages\Element\HolidayParticipation\HolidayParticipationTopCategoryLinks;
use Kanooh\Paddle\Pages\Element\HolidayParticipation\HolidayParticipationMapListSwitcher;
use Kanooh\Paddle\Pages\FrontEndPaddlePage;
use Kanooh\Paddle\Pages\Node\EditPage\HolidayParticipation\HolidayParticipationViewsFiltersForm;

/**
 * The class representing the holidays view page.
 *
 * @property HolidayParticipationPagerLinks $pager
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element[] $offers
 * @property HolidayParticipationTopCategoryLinks $categoryLinks
 * @property HolidayParticipationViewsFiltersForm $exposedFilters
 * @property HolidayParticipationMapListSwitcher $switcher
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element[] $mapElements
 */
class HolidayParticipationOverViewPage extends FrontEndPaddlePage
{

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'pager':
                return new HolidayParticipationPagerLinks($this->webdriver);
                break;
            case 'offers':
                $xpath = '//div[@class="view-content"]//div[contains(@class, "views-row")]';
                $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
                return $elements;
                break;
            case 'categoryLinks':
                return new HolidayParticipationTopCategoryLinks($this->webdriver);
                break;
            case 'exposedFilters':
                return new HolidayParticipationViewsFiltersForm($this->webdriver, $this->webdriver->byTag('form'));
                break;
            case 'switcher':
                return new HolidayParticipationMapListSwitcher($this->webdriver, $this->webdriver->byClassName('hp-offer-display-switcher'));
                break;
            case 'mapElements':
                $xpath = '//div[@class="view-content"]//div[contains(@class, "geofieldMap")]';
                $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
                return $elements;
                break;
        }

        return parent::__get($name);
    }
}
