<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\Embed.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Embed app.
 */
class Embed implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-embed';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_embed';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
