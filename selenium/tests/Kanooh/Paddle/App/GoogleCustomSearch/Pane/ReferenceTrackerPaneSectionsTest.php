<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\GoogleCustomSearch\Pane\ReferenceTrackerPaneSectionsTest.
 */

namespace Kanooh\Paddle\App\GoogleCustomSearch\Pane;

use Kanooh\Paddle\Apps\GoogleCustomSearch;
use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneSectionsTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\GoogleCustomSearchPanelsContentType;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ReferenceTrackerPaneSectionsTest extends ReferenceTrackerPaneSectionsTestBase
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
    protected function configurePaneContentType($content_type, $referenced_ids)
    {
        // No additional configuration needed.
    }
}
