<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\Formbuilder.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Formbuilder app.
 */
class Formbuilder implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-formbuilder';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_formbuilder';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
