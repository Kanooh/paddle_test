<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\CustomCSS.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Custom Theme app.
 */
class CustomCSS implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-custom-css';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_custom_css';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
