<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Apps\MegaDropdown.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Mega Dropdown app.
 */
class MegaDropdown implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-mega-dropdown';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_mega_dropdown';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
