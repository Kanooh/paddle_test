<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\CompanyGuide.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Company Guide app.
 */
class CompanyGuide implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-company-guide';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_company_guide';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return false;
    }
}
