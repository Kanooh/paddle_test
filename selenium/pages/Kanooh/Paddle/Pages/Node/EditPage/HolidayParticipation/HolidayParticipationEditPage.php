<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\HolidayParticipation\HolidayParticipationEditPage.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\HolidayParticipation;

use Kanooh\Paddle\Pages\Node\EditPage\EditPage;

/**
 * Page to edit an offer.
 *
 * @property HolidayParticipationEditForm $holidayParticipationEditForm
 */
class HolidayParticipationEditPage extends EditPage
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'holidayParticipationEditForm':
                return new HolidayParticipationEditForm($this->webdriver, $this->webdriver->byId('offer-node-form'));
        }

        return parent::__get($property);
    }
}
