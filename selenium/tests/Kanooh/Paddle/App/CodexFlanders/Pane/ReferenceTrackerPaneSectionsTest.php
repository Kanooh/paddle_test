<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\CodexFlanders\Pane\ReferenceTrackerPaneSectionsTest.
 */

namespace Kanooh\Paddle\App\CodexFlanders\Pane;

use Kanooh\Paddle\Apps\CodexFlanders;
use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneSectionsTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CodexFlanders\CodexFlandersPanelsContentType;

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

        $this->appService->enableApp(new CodexFlanders);
    }

    /**
     * {@inheritDoc}
     */
    protected function getPaneContentTypeInstance()
    {
        return new CodexFlandersPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type, $referenced_ids)
    {
        // Set a valid url.
        /* @var CodexFlandersPanelsContentType $content_type */
        $content_type->getForm()->url->fill('http://' . $this->alphanumericTestDataProvider->getValidValue() . '.com?AID=125645');
    }
}
