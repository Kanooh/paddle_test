<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\WhoIsWho.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Paddle WhoIsWho paddlet.
 */
class WhoIsWho implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-who-is-who';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_who_is_who';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return False;
    }
}
