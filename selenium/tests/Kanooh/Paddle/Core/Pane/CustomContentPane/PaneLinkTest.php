<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\CustomContentPane\PaneLinkTest.
 */

namespace Kanooh\Paddle\Core\Pane\CustomContentPane;

use Kanooh\Paddle\Core\Pane\Base\PaneLinkTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;

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
        return new CustomContentPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type, $referenced_ids)
    {
        // No additional configuration needed.
    }
}
