<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\SplashPage.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Splash page app.
 */
class SplashPage implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-splash-page';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_splash_page';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return false;
    }
}
