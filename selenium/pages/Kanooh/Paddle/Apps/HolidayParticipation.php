<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Apps\HolidayParticipation.
 */

namespace Kanooh\Paddle\Apps;

class HolidayParticipation implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-holiday-participation';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_holiday_participation';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return false;
    }
}
