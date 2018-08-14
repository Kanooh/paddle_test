<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Apps\OrganizationalUnit.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Organizational Unit app.
 */
class OrganizationalUnit implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-organizational-unit';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_organizational_unit';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return false;
    }
}
