<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\ProtectedContent.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The protected content app.
 */
class ProtectedContent implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-protected-content';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_protected_content';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return false;
    }
}
