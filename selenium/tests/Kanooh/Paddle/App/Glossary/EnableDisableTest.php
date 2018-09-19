<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Glossary\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\Glossary;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\Glossary;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\Glossary
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class EnableDisableTest extends EnableDisableTestBase
{
    /**
     * {@inheritdoc}
     */
    public function getApp()
    {
        return new Glossary;
    }
}
