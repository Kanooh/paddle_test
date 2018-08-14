<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ContactPerson\Pane\ReferenceTrackerPaneEntityReferenceTest.
 */

namespace Kanooh\Paddle\App\ContactPerson\Pane;

use Kanooh\Paddle\Apps\ContactPerson;
use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneEntityReferenceTestBase;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ContactPersonPanelsContentType;

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

        $this->appService->enableApp(new ContactPerson);
    }

    /**
     * {@inheritDoc}
     */
    protected function setUpReferencedEntities()
    {
        $nid = $this->contentCreationService->createContactPerson();

        return array('node' => array($nid));
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
    protected function configurePaneContentType($content_type, $references)
    {
        /* @var ContactPersonPanelsContentType $content_type */
        $content_type->getForm()->contactPersonAutocompleteField->fill('node/' . $references['node'][0]);
        // Pick the suggestion.
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByPosition();
    }
}
