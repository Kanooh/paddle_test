<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\CookieLegislation.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Cookie Legislation app.
 */
class CookieLegislation implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-cookie-legislation';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_cookie_legislation';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
