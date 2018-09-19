<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\MenuStructurePane\PaneSectionsDiffTest.
 */

namespace Kanooh\Paddle\Core\Pane\MenuStructurePane;

use Kanooh\Paddle\Core\Pane\Base\PaneSectionsDiffTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\MenuStructurePanelsContentType;

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
        return new MenuStructurePanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type)
    {
        // No additional configuration needed.
    }
}
