<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\OutgoingRSS.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The OutgoingRSS app.
 */
class OutgoingRSS implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-outgoing-rss';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_outgoing_rss';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
