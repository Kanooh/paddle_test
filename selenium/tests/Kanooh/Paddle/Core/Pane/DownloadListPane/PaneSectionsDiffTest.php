<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\DownloadListPane\PaneSectionsDiffTest.
 */

namespace Kanooh\Paddle\Core\Pane\DownloadListPane;

use Kanooh\Paddle\Core\Pane\Base\PaneSectionsDiffTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\DownloadListPanelsContentType;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneSectionsDiffTest extends PaneSectionsDiffTestBase
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
    protected function configurePaneContentType($content_type)
    {
        /* @var DownloadListPanelsContentType $content_type */
        // Switch to tag mode so we don't need any more configuration.
        $content_type->getForm()->selectionType->select('tags');
    }
}
