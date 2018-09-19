<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\Cirro.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Cirro app.
 */
class Cirro implements AppInterface
{

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-cirro';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_cirro';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return false;
    }
}
