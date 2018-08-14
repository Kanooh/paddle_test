<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Maps\Pane\ReferenceTrackerPaneSectionsTest.
 */

namespace Kanooh\Paddle\App\Maps\Pane;

use Kanooh\Paddle\Apps\Maps;
use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneSectionsTestBase;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\MapsPanelsContentType;

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

        $this->appService->enableApp(new Maps);
    }

    /**
     * {@inheritDoc}
     */
    protected function getPaneContentTypeInstance()
    {
        return new MapsPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type, $referenced_ids)
    {
        // Create a fresh advanced search page to be selected.
        $nid = $this->contentCreationService->createMapsPage();

        // Add this node to the expected references.
        $this->additionalReferences['node'][] = $nid;

        /* @var MapsPanelsContentType $content_type */
        $content_type->getForm()->autocompleteField->fill('node/' . $nid);
        // Pick the suggestion.
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByPosition();
    }
}
