<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\Glossary.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Glossary app.
 */
class Glossary implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-glossary';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_glossary';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return true;
    }
}
