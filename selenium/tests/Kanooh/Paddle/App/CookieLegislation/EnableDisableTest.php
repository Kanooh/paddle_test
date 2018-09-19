<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\CookieLegislation\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\CookieLegislation;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\CookieLegislation;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCookieLegislation\ConfigurePage\ConfigurePage;

/**
 * Class EnableDisableTest.
 *
 * @package Kanooh\Paddle\App\CookieLegislation
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class EnableDisableTest extends EnableDisableTestBase
{
    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->configurePage = new ConfigurePage($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getApp()
    {
        return new CookieLegislation;
    }
}
