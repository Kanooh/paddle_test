<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Apps\ExternalLinks.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The ExternalLinks app.
 */
class ExternalLinks implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-extlink';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_extlink';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
