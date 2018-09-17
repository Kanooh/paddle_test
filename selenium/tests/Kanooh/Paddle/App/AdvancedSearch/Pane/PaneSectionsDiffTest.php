<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\AdvancedSearch\Pane\PaneSectionsDiffTest.
 */

namespace Kanooh\Paddle\App\AdvancedSearch\Pane;

use Kanooh\Paddle\Apps\AdvancedSearch;
use Kanooh\Paddle\Core\Pane\Base\PaneSectionsDiffTestBase;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\AdvancedSearchPanelsContentType;

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

        $this->appService->enableApp(new AdvancedSearch);
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
    protected function configurePaneContentType($content_type)
    {
        // Create a fresh advanced search page to be selected.
        $nid = $this->contentCreationService->createAdvancedSearchPage();

        /* @var AdvancedSearchPanelsContentType $content_type */
        $content_type->getForm()->autocompleteField->fill('node/' . $nid);
        // Pick the suggestion.
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByPosition();
    }
}
