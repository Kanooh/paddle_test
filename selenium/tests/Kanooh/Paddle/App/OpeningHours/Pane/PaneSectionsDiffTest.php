<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OpeningHours\Pane\PaneSectionsDiffTest.
 */

namespace Kanooh\Paddle\App\OpeningHours\Pane;

use Kanooh\Paddle\Apps\OrganizationalUnit;
use Kanooh\Paddle\Apps\OpeningHours;
use Kanooh\Paddle\Core\Pane\Base\PaneSectionsDiffTestBase;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\OpeningHours\OpeningHoursCalendarPanelsContentType;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneSectionsDiffTest extends PaneSectionsDiffTestBase
{
    /**
     * @var string
     */
    protected $ohsTitle;

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
    protected function configurePaneContentType($content_type)
    {
        $nid = $this->contentCreationService->createOrganizationalUnit();

        /* @var OpeningHoursCalendarPanelsContentType $content_type */
        $content_type->getForm()->autocompleteField->fill('node/' . $nid);
        // Pick the suggestion.
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByPosition();
    }
}
