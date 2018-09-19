<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\GoogleCustomSearch\Pane\PaneSectionsDiffTest.
 */

namespace Kanooh\Paddle\App\GoogleCustomSearch\Pane;

use Kanooh\Paddle\Apps\GoogleCustomSearch;
use Kanooh\Paddle\Core\Pane\Base\PaneSectionsDiffTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\GoogleCustomSearchPanelsContentType;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneSectionsDiffTest extends PaneSectionsDiffTestBase
{

    /**
     * {@inheritDoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new GoogleCustomSearch);
    }

    /**
     * {@inheritDoc}
     */
    protected function getPaneContentTypeInstance()
    {
        return new GoogleCustomSearchPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type)
    {
        // No additional configuration needed.
    }
}
