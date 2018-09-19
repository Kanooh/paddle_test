<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Cultuurnet\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\Cultuurnet;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\Cultuurnet;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCultuurnet\ConfigurePage\ConfigurePage;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\Cultuurnet
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
        return new Cultuurnet;
    }
}
