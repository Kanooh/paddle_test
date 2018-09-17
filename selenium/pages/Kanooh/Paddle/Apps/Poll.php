<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\Poll.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Poll app.
 */
class Poll implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-poll';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_poll';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return false;
    }
}
