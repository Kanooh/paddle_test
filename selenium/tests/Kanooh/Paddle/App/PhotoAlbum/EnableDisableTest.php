<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\PhotoAlbum\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\PhotoAlbum;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\PhotoAlbum;

/**
 * Class EnableDisableTest
 * @package Kanooh\Paddle\App\PhotoAlbum
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
        return new PhotoAlbum();
    }
}
