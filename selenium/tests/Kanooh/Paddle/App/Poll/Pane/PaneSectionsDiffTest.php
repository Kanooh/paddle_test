<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Product\Pane\PaneSectionsDiffTest.
 */

namespace Kanooh\Paddle\App\Poll\Pane;

use Kanooh\Paddle\Apps\Poll;
use Kanooh\Paddle\Core\Pane\Base\PaneSectionsDiffTestBase;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\PollPanelsContentType;

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

        $this->appService->enableApp(new Poll);
    }

    /**
     * {@inheritDoc}
     */
    protected function getPaneContentTypeInstance()
    {
        return new PollPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type)
    {
        // Create a fresh organizational unit to be selected.
        $nid = $this->contentCreationService->createPollPage();

        /* @var PollPanelsContentType $content_type */
        $content_type->getForm()->autocompleteField->fill('node/' . $nid);
        // Pick the suggestion.
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByPosition();
    }
}
