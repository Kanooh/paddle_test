<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\CodexFlanders.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The CodexFlanders app.
 */
class CodexFlanders implements AppInterface
{

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-codex-flanders';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_codex_flanders';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return false;
    }
}
