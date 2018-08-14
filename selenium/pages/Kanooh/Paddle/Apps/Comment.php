<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\Interaction.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Comment app.
 */
class Comment implements AppInterface
{

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-comment';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_comment';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
