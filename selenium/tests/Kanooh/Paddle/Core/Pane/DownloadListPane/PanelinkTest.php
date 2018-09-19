<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\DownloadListPane\PaneLinkTest.
 */

namespace Kanooh\Paddle\Core\Pane\DownloadListPane;

use Kanooh\Paddle\Core\Pane\Base\PaneLinkTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\DownloadListPanelsContentType;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneLinkTest extends PaneLinkTestBase
{
    /**
     * {@inheritDoc}
     */
    protected function getPaneContentTypeInstance()
    {
        return new DownloadListPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type, $referenced_ids)
    {
        /* @var DownloadListPanelsContentType $content_type */
        // Switch to tag mode so we don't need any more configuration.
        $content_type->getForm()->selectionType->select('tags');
    }
}
