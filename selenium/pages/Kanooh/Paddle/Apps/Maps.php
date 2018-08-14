<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\Maps.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Maps app.
 */
class Maps implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-maps';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_maps';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
