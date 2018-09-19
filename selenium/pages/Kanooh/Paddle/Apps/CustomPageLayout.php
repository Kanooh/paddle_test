<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\CustomPageLayout.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Custom Page Layout app.
 */
class CustomPageLayout implements AppInterface
{

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-custom-page-layout';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_custom_page_layout';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
