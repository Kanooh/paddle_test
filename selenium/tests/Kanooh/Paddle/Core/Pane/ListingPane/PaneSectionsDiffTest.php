<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\ListingPane\PaneSectionsDiffTest.
 */

namespace Kanooh\Paddle\Core\Pane\ListingPane;

use Kanooh\Paddle\Core\Pane\Base\PaneSectionsDiffTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ListingPanelsContentType;

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
        return new ListingPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type)
    {
        // No additional configuration needed.
    }
}
