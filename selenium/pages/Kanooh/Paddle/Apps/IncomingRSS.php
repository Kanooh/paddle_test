<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\IncomingRSS.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The IncomingRSS app.
 */
class IncomingRSS implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-incoming-rss';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_incoming_rss';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
