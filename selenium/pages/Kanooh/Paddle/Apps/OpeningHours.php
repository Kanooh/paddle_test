<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\OpeningHours.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Opening hours app.
 */
class OpeningHours implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-opening-hours';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_opening_hours';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
