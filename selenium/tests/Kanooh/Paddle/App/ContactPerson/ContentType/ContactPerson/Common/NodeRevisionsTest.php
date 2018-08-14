<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\ContactPerson\ContentType\ContactPerson\Common\NodeRevisionsTest.
 */

namespace Kanooh\Paddle\App\ContactPerson\ContentType\ContactPerson\Common;

use Kanooh\Paddle\Apps\ContactPerson;
use Kanooh\Paddle\Apps\OrganizationalUnit;
use Kanooh\Paddle\Core\ContentType\Base\NodeRevisionsTestBase;
use Kanooh\Paddle\Pages\Node\EditPage\ContactPerson\ContactPersonEditPage;
use Kanooh\Paddle\Pages\Node\EditPage\ContactPersonRandomFiller;
use Kanooh\Paddle\Utilities\AssetCreationService;

/**
 * Class NodeRevisionsTest
 * @package Kanooh\Paddle\App\ContactPerson\ContentType\ContactPerson\Common
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NodeRevisionsTest extends NodeRevisionsTestBase
{
    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * The form filler for the contact person edit form.
     *
     * @var ContactPersonRandomFiller
     */
    protected $contactPersonRandomFiller;

    /**
     * The contact person edit page.
     *
     * @var ContactPersonEditPage
     */
    protected $editContactPersonPage;

    /**
     * {@inheritdoc}
     */
    public function setupPage()
    {
        parent::setUpPage();

        $this->assetCreationService = new AssetCreationService($this);
        $this->contactPersonRandomFiller = new ContactPersonRandomFiller();
        $this->editContactPersonPage = new ContactPersonEditPage($this);
        $this->service->enableApp(new ContactPerson);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createContactPersonViaUI($title);
    }

    /**
     * Tests the contact person specific fields.
     *
     * @group revisions
     */
    public function testContactPersonRevision()
    {
        // Disable paddle_OU.
        $this->service->disableApp(new OrganizationalUnit);

        $atom = $this->assetCreationService->createImage();

        // Create a node, fill out the edit page and save.
        $nid = $this->contentCreationService->createContactPerson();
        $this->editContactPersonPage->go($nid);
        $this->editContactPersonPage->form->photo->selectAtom($atom['id']);
        $this->contactPersonRandomFiller->randomize();
        $this->contactPersonRandomFiller->fill($this->editContactPersonPage);
        $this->editContactPersonPage->contextualToolbar->buttonSave->click();

        // Get the revisions for this node.
        $revisions = node_revision_list(node_load($nid));
        $revisions = array_slice($revisions, 0, 2, true);
        $vids = array_keys($revisions);

        // Go to the diff page and check if the required fields are there.
        $this->diffPage->go(array($nid, $vids[1], $vids[0]));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent($this->contactPersonRandomFiller->locationTitle));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent($this->contactPersonRandomFiller->addressStreet));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent($this->contactPersonRandomFiller->addressPostalCode . ' ' . $this->contactPersonRandomFiller->addressCity));

        // Check the rest of the fields.
        $this->checkFieldsTextDiff();

        // Now enable paddle_OU and check again.
        $this->service->enableApp(new OrganizationalUnit);

        // Check the rest of the fields.
        $this->checkFieldsTextDiff();
    }

    /**
     * Helper function to check the field values.
     */
    protected function checkFieldsTextDiff()
    {
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent($this->contactPersonRandomFiller->linkedin));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent($this->contactPersonRandomFiller->twitter));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent($this->contactPersonRandomFiller->website));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent($this->contactPersonRandomFiller->yammer));

        // Get an identifier to check for in the src of the rendered image.
        $this->assertTrue($this->diffPage->checkImagePresent('sample_image'));

        // Verify that no computed fields are present.
        $this->assertTextNotPresent('Computed fields');

        // Verify there are no error messages.
        $this->assertEmpty($this->messages->errorMessages());
    }
}
