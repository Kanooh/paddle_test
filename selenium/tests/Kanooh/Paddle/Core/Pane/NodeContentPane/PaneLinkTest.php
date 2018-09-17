<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\NodeContentPane\PaneLinkTest.
 */

namespace Kanooh\Paddle\Core\Pane\NodeContentPane;

use Kanooh\Paddle\Core\Pane\Base\PaneLinkTestBase;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\NodeContentPanelsContentType;

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
        return new NodeContentPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type, $referenced_ids)
    {
        // Create a fresh node to be selected.
        $nid = $this->contentCreationService->createBasicPage();

        /* @var NodeContentPanelsContentType $content_type */
        $content_type->getForm()->nodeContentAutocomplete->fill('node/' . $nid);
        // Pick the suggestion.
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByPosition();
    }
}
