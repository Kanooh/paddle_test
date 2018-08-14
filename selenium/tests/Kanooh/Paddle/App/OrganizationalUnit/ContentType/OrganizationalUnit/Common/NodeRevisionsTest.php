<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OrganizationalUnit\ContentType\OrganizationalUnit\Common\NodeRevisionsTest.
 */

namespace Kanooh\Paddle\App\OrganizationalUnit\ContentType\OrganizationalUnit\Common;

use Kanooh\Paddle\Apps\OrganizationalUnit;
use Kanooh\Paddle\Apps\ContactPerson;
use Kanooh\Paddle\Core\ContentType\Base\NodeRevisionsTestBase;
use Kanooh\Paddle\Pages\Node\EditPage\EditOrganizationalUnitPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditOrganizationalUnitPageRandomFiller;

/**
 * Class NodeRevisionsTest
 * @package Kanooh\Paddle\App\OrganizationalUnit\ContentType\OrganizationalUnit\Common
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeRevisionsTest extends NodeRevisionsTestBase
{
    /**
     * The form filler for the organizational unit edit form.
     *
     * @var EditOrganizationalUnitPageRandomFiller
     */
    protected $organizationalUnitRandomFiller;

    /**
     * The organizational unit edit page.
     *
     * @var EditOrganizationalUnitPage
     */
    protected $editOrganizationalUnitPage;

    /**
     * {@inheritdoc}
     */
    public function setupPage()
    {
        parent::setUpPage();

        $this->organizationalUnitRandomFiller = new EditOrganizationalUnitPageRandomFiller();
        $this->editOrganizationalUnitPage = new EditOrganizationalUnitPage($this);
        $this->service->enableApp(new OrganizationalUnit);
        $app = new ContactPerson;
        $this->service->disableAppsByMachineNames(array($app->getModuleName()));
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createOrganizationalUnitViaUI($title);
    }

    /**
     * Tests the organizational unit specific fields.
     *
     * @group revisions
     */
    public function testOrganizationalUnitRevision()
    {
        // Create a node, fill out the edit page and save.
        $nid = $this->contentCreationService->createOrganizationalUnit();
        $this->editOrganizationalUnitPage->go($nid);
        $this->organizationalUnitRandomFiller->randomize();
        $this->organizationalUnitRandomFiller->fill($this->editOrganizationalUnitPage);
        $this->editOrganizationalUnitPage->contextualToolbar->buttonSave->click();

        // Get the revisions for this node.
        $revisions = node_revision_list(node_load($nid));
        $revisions = array_slice($revisions, 0, 2, true);
        $vids = array_keys($revisions);

        // Go to the diff page and check if the required fields are there.
        $this->diffPage->go(array($nid, $vids[1], $vids[0]));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent($this->organizationalUnitRandomFiller->locationName));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent($this->organizationalUnitRandomFiller->locationStreet));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent($this->organizationalUnitRandomFiller->locationPostalCode . ' ' . $this->organizationalUnitRandomFiller->locationCity));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent($this->organizationalUnitRandomFiller->website));

        // Verify that no computed fields are present.
        $this->assertTextNotPresent('Computed fields');

        // Verify there are no error messages.
        $this->assertEmpty($this->messages->errorMessages());
    }
}
