<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ContactPerson\TopSectionImagePresenceTest.
 */

namespace Kanooh\Paddle\App\ContactPerson;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\ContactPerson;
use Kanooh\Paddle\Core\Pane\Base\TopSectionImagePresenceTestBase;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ContactPersonPanelsContentType;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class TopSectionImagePresenceTest extends TopSectionImagePresenceTestBase
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
     * {@inheritdoc}
     */
    public function createPaneWithTopImage($nid)
    {
        // Create a contact person to select in the pane.
        $contact_person_nid = $this->contentCreationService->createContactPerson();

        // Add a contact person pane to the test node.
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();
        $content_type = new ContactPersonPanelsContentType($this);

        $webdriver = $this;
        $callable = new SerializableClosure(
            function () use ($webdriver, $content_type, $contact_person_nid) {
                $content_type->getForm()->contactPersonAutocompleteField->fill('node/' . $contact_person_nid);
                $autocomplete = new AutoComplete($webdriver);
                $autocomplete->pickSuggestionByPosition(0);
            }
        );
        $pane = $region->addPane($content_type, $callable);

        // Edit it to add top image to it.
        $this->addTopImageToPane($pane, $content_type);

        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        return $pane->getUuid();
    }
}
