<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\SimpleContact.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Simple Contact app.
 */
class SimpleContact implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-simple-contact';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_simple_contact';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
