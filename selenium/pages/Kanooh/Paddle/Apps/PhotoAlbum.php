<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\PhotoAlbum.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Photo Album app.
 */
class PhotoAlbum implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-photo-album';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_photo_album';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return false;
    }
}
