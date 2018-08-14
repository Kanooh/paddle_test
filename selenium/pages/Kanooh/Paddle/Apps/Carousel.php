<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\Carousel.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Carousel app.
 */
class Carousel implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-carousel';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_carousel';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return false;
    }
}
