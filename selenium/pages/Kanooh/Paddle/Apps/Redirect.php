<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\Redirect.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Paddle Redirect paddlet.
 */
class Redirect implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-redirect';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_redirect';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
