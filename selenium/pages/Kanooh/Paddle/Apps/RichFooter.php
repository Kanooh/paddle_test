<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Apps\RichFooter.
 */

namespace Kanooh\Paddle\Apps;

class RichFooter implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-rich-footer';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_rich_footer';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
