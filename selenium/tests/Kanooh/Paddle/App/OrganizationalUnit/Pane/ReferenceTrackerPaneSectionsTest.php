<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OrganizationalUnit\Pane\ReferenceTrackerPaneSectionsTest.
 */

namespace Kanooh\Paddle\App\OrganizationalUnit\Pane;

use Kanooh\Paddle\Apps\OrganizationalUnit;
use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneSectionsTestBase;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\OrganizationalUnitPanelsContentType;

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
    }

    /**
     * {@inheritDoc}
     */
    protected function getPaneContentTypeInstance()
    {
        return new OrganizationalUnitPanelsContentType($this);
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

        /* @var OrganizationalUnitPanelsContentType $content_type */
        $content_type->getForm()->organizationalUnitAutocompleteField->fill('node/' . $nid);
        // Pick the suggestion.
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByPosition();
    }
}
