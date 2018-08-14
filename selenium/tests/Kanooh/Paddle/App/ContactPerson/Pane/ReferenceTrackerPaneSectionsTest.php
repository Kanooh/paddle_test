<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ContactPerson\Pane\ReferenceTrackerPaneSectionsTest.
 */

namespace Kanooh\Paddle\App\ContactPerson\Pane;

use Kanooh\Paddle\Apps\ContactPerson;
use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneSectionsTestBase;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ContactPersonPanelsContentType;

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
    protected function configurePaneContentType($content_type, $referenced_ids)
    {
        // Create a fresh contact person to be selected.
        $nid = $this->contentCreationService->createContactPerson();

        // Add this node to the expected references.
        $this->additionalReferences['node'][] = $nid;

        /* @var ContactPersonPanelsContentType $content_type */
        $content_type->getForm()->contactPersonAutocompleteField->fill('node/' . $nid);
        // Pick the suggestion.
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByPosition();
    }
}
