<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Apps\GoogleAnalytics.
 */

namespace Kanooh\Paddle\Apps;

class GoogleAnalytics implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-google-analytics';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_google_analytics';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
