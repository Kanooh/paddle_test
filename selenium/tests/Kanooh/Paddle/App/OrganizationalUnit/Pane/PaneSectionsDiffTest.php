<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OrganizationalUnit\Pane\PaneSectionsDiffTest.
 */

namespace Kanooh\Paddle\App\OrganizationalUnit\Pane;

use Kanooh\Paddle\Apps\OrganizationalUnit;
use Kanooh\Paddle\Core\Pane\Base\PaneSectionsDiffTestBase;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\OrganizationalUnitPanelsContentType;

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
    protected function configurePaneContentType($content_type)
    {
        // Create a fresh organizational unit to be selected.
        $nid = $this->contentCreationService->createOrganizationalUnit();

        /* @var OrganizationalUnitPanelsContentType $content_type */
        $content_type->getForm()->organizationalUnitAutocompleteField->fill('node/' . $nid);
        // Pick the suggestion.
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByPosition();
    }
}
