<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\CodexFlanders\Pane\PaneSectionsDiffTest.
 */

namespace Kanooh\Paddle\App\CodexFlanders\Pane;

use Kanooh\Paddle\Apps\CodexFlanders;
use Kanooh\Paddle\Core\Pane\Base\PaneSectionsDiffTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CodexFlanders\CodexFlandersPanelsContentType;

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
    protected function configurePaneContentType($content_type)
    {
        // Set a valid url.
        /* @var CodexFlandersPanelsContentType $content_type */
        $content_type->getForm()->url->fill('http://' . $this->alphanumericTestDataProvider->getValidValue() . '.com?AID=125645');
    }
}
