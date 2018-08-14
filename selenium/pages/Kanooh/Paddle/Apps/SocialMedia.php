<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\SocialMedia.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Paddle Social Media paddlet.
 */
class SocialMedia implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-social-media';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_social_media';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
