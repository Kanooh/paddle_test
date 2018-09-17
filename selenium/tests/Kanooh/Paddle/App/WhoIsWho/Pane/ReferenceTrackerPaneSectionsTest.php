<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\WhoIsWho\Pane\ReferenceTrackerPaneSectionsTest.
 */

namespace Kanooh\Paddle\App\WhoIsWho\Pane;

use Kanooh\Paddle\Apps\WhoIsWho;
use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneSectionsTestBase;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\WhoIsWho\WhoIsWhoPanelsContentType;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ReferenceTrackerPaneSectionsTest extends ReferenceTrackerPaneSectionsTestBase
{
    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var string
     */
    protected $nid;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new WhoIsWho);
    }

    /**
     * {@inheritDoc}
     */
    protected function getPaneContentTypeInstance()
    {
        return new WhoIsWhoPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function additionalTestSetUp()
    {
        // Create a fresh organizational unit to be selected and add it to
        // a contact person.
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $ou_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->nid = $this->contentCreationService->createOrganizationalUnit($ou_title);
        $cp_nid = $this->contentCreationService->createContactPerson();
        $this->contentCreationService->fillContactPersonWithRandomValues($cp_nid, $ou_title);

        // Publish the OU and CP.
        $this->contentCreationService->moderateNode($this->nid, workbench_moderation_state_published());
        $this->contentCreationService->moderateNode($cp_nid, workbench_moderation_state_published());
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type, $references)
    {
        /* @var WhoIsWhoPanelsContentType $content_type */
        $content_type->getForm()->autocompleteField->fill('node/' . $this->nid);

        // Add this node to the expected references.
        $this->additionalReferences['node'][] = $this->nid;

        // Pick the suggestion.
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilDisplayed();
        $autocomplete->pickSuggestionByPosition();
    }
}
