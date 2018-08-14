<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\SocialIdentities.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Paddle Social Identities paddlet.
 */
class SocialIdentities implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-social-identities';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_social_identities';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
