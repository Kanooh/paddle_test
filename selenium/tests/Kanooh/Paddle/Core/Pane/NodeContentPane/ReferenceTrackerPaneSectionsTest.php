<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\NodeContentPane\ReferenceTrackerPaneSectionsTest.
 */

namespace Kanooh\Paddle\Core\Pane\NodeContentPane;

use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneSectionsTestBase;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\NodeContentPanelsContentType;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ReferenceTrackerPaneSectionsTest extends ReferenceTrackerPaneSectionsTestBase
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
    protected function configurePaneContentType($content_type, $referenced_ids)
    {
        // Create a fresh node to be selected.
        $nid = $this->contentCreationService->createBasicPage();

        // Add this node to the expected references.
        $this->additionalReferences['node'][] = $nid;

        /* @var NodeContentPanelsContentType $content_type */
        $content_type->getForm()->nodeContentAutocomplete->fill('node/' . $nid);
        // Pick the suggestion.
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByPosition();
    }
}
