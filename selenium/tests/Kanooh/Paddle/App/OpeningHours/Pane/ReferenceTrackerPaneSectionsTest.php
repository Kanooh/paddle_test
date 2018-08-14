<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OpeningHours\Pane\ReferenceTrackerPaneSectionsTest.
 */

namespace Kanooh\Paddle\App\OpeningHours\Pane;

use Kanooh\Paddle\Apps\OpeningHours;
use Kanooh\Paddle\Apps\OrganizationalUnit;
use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneSectionsTestBase;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\OpeningHours\OpeningHoursCalendarPanelsContentType;

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

        $this->appService->enableApp(new OrganizationalUnit);
        $this->appService->enableApp(new OpeningHours);
    }

    /**
     * {@inheritDoc}
     */
    protected function getPaneContentTypeInstance()
    {
        return new OpeningHoursCalendarPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type, $referenced_ids)
    {
        // Create a fresh organizational unit to be selected.
        $nid = $this->contentCreationService->createOrganizationalUnit();

        // Add this node to the expected references.
        $this->additionalReferences['node'][] = $nid;

        /* @var OpeningHoursCalendarPanelsContentType $content_type */
        $content_type->getForm()->autocompleteField->fill('node/' . $nid);
        // Pick the suggestion.
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByPosition();
    }
}
