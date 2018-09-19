<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Apps\Cultuurnet.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Cultuurnet App.
 */
class Cultuurnet implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-cultuurnet';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_cultuurnet';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
