<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\FacetedSearch.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Faceted Search app.
 */
class FacetedSearch implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-faceted-search';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_faceted_search';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
