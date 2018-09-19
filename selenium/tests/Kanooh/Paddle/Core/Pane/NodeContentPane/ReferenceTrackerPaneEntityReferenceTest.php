<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\NodeContentPane\ReferenceTrackerPaneEntityReferenceTest.
 */

namespace Kanooh\Paddle\Core\Pane\NodeContentPane;

use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneEntityReferenceTestBase;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\NodeContentPanelsContentType;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ReferenceTrackerPaneEntityReferenceTest extends ReferenceTrackerPaneEntityReferenceTestBase
{

    /**
     * {@inheritDoc}
     */
    protected function getPaneContentTypeInstance()
    {
        return new NodeContentPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type, $references)
    {
        /* @var NodeContentPanelsContentType $content_type */
        $content_type->getForm()->nodeContentAutocomplete->fill('node/' . $references['node'][0]);
        // Pick the suggestion.
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByPosition();
    }
}
