<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\ReCaptcha.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The ReCaptcha app.
 */
class ReCaptcha implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-recaptcha';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_recaptcha';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
