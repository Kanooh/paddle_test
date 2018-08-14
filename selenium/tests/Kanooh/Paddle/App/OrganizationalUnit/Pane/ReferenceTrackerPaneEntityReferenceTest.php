<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OrganizationalUnit\Pane\ReferenceTrackerPaneEntityReferenceTest.
 */

namespace Kanooh\Paddle\App\OrganizationalUnit\Pane;

use Kanooh\Paddle\Apps\OrganizationalUnit;
use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneEntityReferenceTestBase;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\OrganizationalUnitPanelsContentType;

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

        $this->appService->enableApp(new OrganizationalUnit);
    }

    /**
     * {@inheritDoc}
     */
    protected function setUpReferencedEntities()
    {
        $nid = $this->contentCreationService->createOrganizationalUnit();

        return array('node' => array($nid));
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
    protected function configurePaneContentType($content_type, $references)
    {
        /* @var OrganizationalUnitPanelsContentType $content_type */
        $content_type->getForm()->organizationalUnitAutocompleteField->fill('node/' . $references['node'][0]);
        // Pick the suggestion.
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByPosition();
    }
}
