<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\News\Pane\ReferenceTrackerPaneSectionsTest.
 */

namespace Kanooh\Paddle\App\News\Pane;

use Kanooh\Paddle\Apps\News;
use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneSectionsTestBase;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\News\NewsPanelsContentType;

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

        $this->appService->enableApp(new News);
    }

    /**
     * {@inheritDoc}
     */
    protected function getPaneContentTypeInstance()
    {
        return new NewsPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type, $referenced_ids)
    {
        // Create a fresh news item to be selected.
        $nid = $this->contentCreationService->createNewsItem();

        // Add this node to the expected references.
        $this->additionalReferences['node'][] = $nid;

        /* @var NewsPanelsContentType $content_type */
        $content_type->getForm()->newsAutocompleteField->fill('node/' . $nid);
        // Pick the suggestion.
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByPosition();
    }
}
