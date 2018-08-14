<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\FlyOutMenu.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Paddle Fly-out Menu paddlet.
 */
class FlyOutMenu implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-fly-out-menu';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_fly_out_menu';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return false;
    }
}
