<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\AdvancedSearch\Pane\ReferenceTrackerPaneEntityReferenceTest.
 */

namespace Kanooh\Paddle\App\AdvancedSearch\Pane;

use Kanooh\Paddle\Apps\AdvancedSearch;
use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneEntityReferenceTestBase;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\AdvancedSearchPanelsContentType;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ReferenceTrackerPaneEntityReferenceTest extends ReferenceTrackerPaneEntityReferenceTestBase
{

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new AdvancedSearch);
    }

    /**
     * {@inheritDoc}
     */
    protected function setUpReferencedEntities()
    {
        $nid = $this->contentCreationService->createAdvancedSearchPage();

        return array('node' => array($nid));
    }

    /**
     * {@inheritDoc}
     */
    protected function getPaneContentTypeInstance()
    {
        return new AdvancedSearchPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type, $references)
    {
        /* @var AdvancedSearchPanelsContentType $content_type */
        $content_type->getForm()->autocompleteField->fill('node/' . $references['node'][0]);
        // Pick the suggestion.
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByPosition();
    }
}
