<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\Rate.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Rate app.
 */
class Rate implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-rate';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_rate';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
