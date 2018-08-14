<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\DownloadListPane\ReferenceTrackerPaneSectionsTest.
 */

namespace Kanooh\Paddle\Core\Pane\DownloadListPane;

use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneSectionsTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\DownloadListPanelsContentType;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ReferenceTrackerPaneSectionsTest extends ReferenceTrackerPaneSectionsTestBase
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
