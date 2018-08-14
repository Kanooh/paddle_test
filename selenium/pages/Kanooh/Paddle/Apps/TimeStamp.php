<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\TimeStamp.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Paddle Timestamp paddlet.
 */
class TimeStamp implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-timestamp';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_timestamp';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
