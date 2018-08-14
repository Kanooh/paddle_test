<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OrganizationalUnit\ContentType\OrganizationalUnit\Common\NodeArchiveTest.
 */

namespace Kanooh\Paddle\App\OrganizationalUnit\ContentType\OrganizationalUnit\Common;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\OrganizationalUnit;
use Kanooh\Paddle\Core\ContentType\Base\NodeArchiveTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\OrganizationalUnitPanelsContentType;
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

        $this->appService->enableApp(new OrganizationalUnit);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createOrganizationalUnit($title);
    }

    /**
     * Tests that the organizational unit pane autocomplete do not suggest
     * archived pages.
     *
     * @group workflow
     * @group archive
     */
    public function testPaneAutocompletesExcludeArchivedPages()
    {
        // Delete all organizational unit nodes to make sure in the autocomplete
        // we get only the archived node and the other one.
        $clean_up_service = new CleanUpService($this);
        $clean_up_service->deleteEntities('node', 'organizational_unit');

        $this->userSessionService->login('ChiefEditor');

        // Create a node to make sure at least 1 will appear in the autocomplete.
        $this->setupNode();

        // Create a archived test node.
        $node = array();
        $node['title'] = $this->alphanumericTestDataProvider->getValidValue();
        $node['nid'] = $this->setupNode($node['title']);
        $this->contentCreationService->moderateNode($node['nid'], 'archived');

        // Test that the archived node doesn't appear in the organizational unit
        // pane autocomplete.
        $basic_page_nid = $this->contentCreationService->createBasicPage();
        $this->layoutPage->go($basic_page_nid);

        // Add a organizational unit pane to the basic page.
        $content_type = new OrganizationalUnitPanelsContentType($this);
        $webdriver = $this;
        $callable = new SerializableClosure(
            function () use ($webdriver, $content_type, $node) {
                $content_type->getForm()->organizationalUnitAutocompleteField->fill('node/');
                $webdriver->assertAutocompleteNotContainsNode($node);
            }
        );
        $region = $this->layoutPage->display->getRandomRegion();
        $region->addPane($content_type, $callable);
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
    }
}
