<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\Multilingual.
 */

namespace Kanooh\Paddle\Apps;

/**
 * Class representing the i18n paddlet.
 */
class Multilingual implements AppInterface
{
    /**
    * {@inheritdoc}
    */
    public function getId()
    {
        return 'paddle-i18n';
    }

    /**
    * {@inheritdoc}
    */
    public function getModuleName()
    {
        return 'paddle_i18n';
    }

    /**
    * {@inheritdoc}
    */
    public function isConfigurable()
    {
        return true;
    }
}
