<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Apps\GoogleCustomSearch.
 */

namespace Kanooh\Paddle\Apps;

class GoogleCustomSearch implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-google-custom-search';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_google_custom_search';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return false;
    }
}
