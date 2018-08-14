<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\Calendar.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Calendar app.
 */
class Calendar implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-calendar';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_calendar';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return false;
    }
}
