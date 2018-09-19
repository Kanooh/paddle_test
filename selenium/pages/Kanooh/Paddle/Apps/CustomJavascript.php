<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\CustomJavascript.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Custom Javascript app.
 */
class CustomJavascript implements AppInterface
{

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-custom-javascript';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_custom_javascript';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
