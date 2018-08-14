<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\MenuStructurePane\PaneLinkTest.
 */

namespace Kanooh\Paddle\Core\Pane\MenuStructurePane;

use Kanooh\Paddle\Core\Pane\Base\PaneLinkTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\MenuStructurePanelsContentType;

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
        return new MenuStructurePanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type, $referenced_ids)
    {
        // No additional configuration needed.
    }
}
