<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ContactPerson\ContentType\ContactPerson\Common\NodeArchiveTest.
 */

namespace Kanooh\Paddle\App\ContactPerson\ContentType\ContactPerson\Common;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\ContactPerson;
use Kanooh\Paddle\Core\ContentType\Base\NodeArchiveTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ContactPersonPanelsContentType;
use Kanooh\Paddle\Utilities\CleanUpService;

/**
 * NodeArchiveTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeArchiveTest extends NodeArchiveTestBase
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
    public function setupNode($first_name = null, $last_name = null)
    {
        return $this->contentCreationService->createContactPerson($first_name, $last_name);
    }

    /**
     * Tests that the contact person pane autocomplete do not suggest archived pages.
     *
     * @group workflow
     * @group archive
     */
    public function testPaneAutocompletesExcludeArchivedPages()
    {
        // Delete all contact person nodes to make sure in the autocomplete we
        // get only the archived node and the other one.
        $clean_up_service = new CleanUpService($this);
        $clean_up_service->deleteEntities('node', 'contact_person');

        $this->userSessionService->login('ChiefEditor');

        // Create a node to make sure at least 1 will appear in the autocomplete.
        $this->setupNode();

        // Create a archived test node.
        $node = array();
        $node['title'] = $this->alphanumericTestDataProvider->getValidValue();
        $node['nid'] = $this->setupNode($node['title']);
        $this->contentCreationService->moderateNode($node['nid'], 'archived');

        // Test that the archived node doesn't appear in the contact person pane
        // autocomplete.
        $basic_page_nid = $this->contentCreationService->createBasicPage();
        $this->layoutPage->go($basic_page_nid);

        // Add a contact person pane to the basic page.
        $content_type = new ContactPersonPanelsContentType($this);
        $webdriver = $this;
        $callable = new SerializableClosure(
            function () use ($webdriver, $content_type, $node) {
                $content_type->getForm()->contactPersonAutocompleteField->fill('node/');
                $webdriver->assertAutocompleteNotContainsNode($node);
            }
        );
        $region = $this->layoutPage->display->getRandomRegion();
        $region->addPane($content_type, $callable);
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
    }
}
