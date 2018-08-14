<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OpeningHours\Pane\ReferenceTrackerPaneEntityReferenceTest.
 */

namespace Kanooh\Paddle\App\OpeningHours\Pane;

use Kanooh\Paddle\Apps\OpeningHours;
use Kanooh\Paddle\Apps\OrganizationalUnit;
use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneEntityReferenceTestBase;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\OpeningHours\OpeningHoursCalendarPanelsContentType;

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
        $this->appService->enableApp(new OpeningHours);
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
        return new OpeningHoursCalendarPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type, $references)
    {
        /* @var OpeningHoursCalendarPanelsContentType $content_type */
        $content_type->getForm()->autocompleteField->fill('node/' . $references['node'][0]);
        // Pick the suggestion.
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByPosition();
    }
}
