<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\HolidayParticipation.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage\HolidayParticipation;

use Kanooh\Paddle\Pages\FrontEndPaddlePage;
use Kanooh\Paddle\Pages\Element\HolidayParticipation\HolidayParticipationMapListSwitcher;

/**
 * The class representing the Holiday participation maps view page.
 *
 * @property HolidayParticipationMapListSwitcher $switcher
 */
class HolidayParticipationMapsViewPage extends FrontEndPaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'aanbod/%/kaart';

    /**
     * {@inheritdoc}
     */
    public function waitUntilPageIsLoaded()
    {
        $this->webdriver->waitUntilElementIsDisplayed('//div[contains(@class, "view-content")]');
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'switcher':
                return new HolidayParticipationMapListSwitcher($this->webdriver);
                break;
        }

        return parent::__get($name);
    }
}
