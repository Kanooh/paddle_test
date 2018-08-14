<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\ListingPane\PaneLinkTest.
 */

namespace Kanooh\Paddle\Core\Pane\ListingPane;

use Kanooh\Paddle\Core\Pane\Base\PaneLinkTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ListingPanelsContentType;

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
        return new ListingPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type, $referenced_ids)
    {
        // No additional configuration needed.
    }
}
