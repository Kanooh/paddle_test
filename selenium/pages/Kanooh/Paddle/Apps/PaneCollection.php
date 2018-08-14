<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\PaneCollection.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Pane Collection app.
 */
class PaneCollection implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-pane-collection';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_pane_collection';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
