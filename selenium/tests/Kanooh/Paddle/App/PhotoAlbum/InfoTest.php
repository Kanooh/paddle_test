<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\PhotoAlbum\InfoTest.
 */

namespace Kanooh\Paddle\App\PhotoAlbum;

use Kanooh\Paddle\App\Base\InfoTestBase;
use Kanooh\Paddle\Apps\PhotoAlbum;

/**
 * Class InfoTest
 * @package Kanooh\Paddle\App\PhotoAlbum
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class InfoTest extends InfoTestBase
{
    /**
     * {@inheritdoc}
     */
    public function getApp()
    {
        return new PhotoAlbum;
    }
}
