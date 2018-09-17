<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ContactPerson\Pane\PaneSectionsDiffTest.
 */

namespace Kanooh\Paddle\App\ContactPerson\Pane;

use Kanooh\Paddle\Apps\ContactPerson;
use Kanooh\Paddle\Core\Pane\Base\PaneSectionsDiffTestBase;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ContactPersonPanelsContentType;

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

        $this->appService->enableApp(new ContactPerson);
    }

    /**
     * {@inheritDoc}
     */
    protected function getPaneContentTypeInstance()
    {
        return new ContactPersonPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type)
    {
        // Create a fresh contact person to be selected.
        $nid = $this->contentCreationService->createContactPerson();
        
        /* @var ContactPersonPanelsContentType $content_type */
        $content_type->getForm()->contactPersonAutocompleteField->fill('node/' . $nid);
        // Pick the suggestion.
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByPosition();
    }
}
