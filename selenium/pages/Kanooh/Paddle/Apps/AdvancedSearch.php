<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\AdvancedSearch.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Advanced Search app.
 */
class AdvancedSearch implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-advanced-search';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_advanced_search';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return false;
    }
}
