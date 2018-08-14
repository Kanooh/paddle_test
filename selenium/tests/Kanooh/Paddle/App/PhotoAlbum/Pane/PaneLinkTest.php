<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\PhotoAlbum\Pane\PaneLinkTest.
 */

namespace Kanooh\Paddle\App\PhotoAlbum\Pane;

use Kanooh\Paddle\Apps\PhotoAlbum;
use Kanooh\Paddle\Core\Pane\Base\PaneLinkTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\PhotoAlbum\PhotoAlbumPanelsContentType;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneLinkTest extends PaneLinkTestBase
{

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new PhotoAlbum);
    }

    /**
     * {@inheritDoc}
     */
    protected function getPaneContentTypeInstance()
    {
        return new PhotoAlbumPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type, $referenced_ids)
    {
        // Do nothing.
    }
}
