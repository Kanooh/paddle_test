<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\CustomContentPane\PaneSectionsDiffTest.
 */

namespace Kanooh\Paddle\Core\Pane\CustomContentPane;

use Kanooh\Paddle\Core\Pane\Base\PaneSectionsDiffTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;

/**
 * Class PaneSectionsDiffTest.
 *
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
        return new CustomContentPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type)
    {
        // No additional configuration needed.
    }
}
