<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\Ebl.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The EBL app.
 */
class Ebl implements AppInterface
{

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-ebl';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_ebl';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return false;
    }
}
