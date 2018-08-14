<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\News.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Contact Person app.
 */
class News implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-news';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_news';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return false;
    }
}
