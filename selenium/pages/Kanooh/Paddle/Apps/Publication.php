<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\Publication.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Publication app.
 */
class Publication implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-publication';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_publication';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return false;
    }
}
