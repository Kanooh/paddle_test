<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Apps\SectionTheming.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Section theming app.
 */
class SectionTheming implements AppInterface
{

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-section-theming';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_section_theming';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return false;
    }
}
