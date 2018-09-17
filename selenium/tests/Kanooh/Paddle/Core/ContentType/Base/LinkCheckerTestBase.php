<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\Base\LinkCheckerTestBase.
 */

namespace Kanooh\Paddle\Core\ContentType\Base;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Admin\Archive\ArchiveNodeModal;
use Kanooh\Paddle\Pages\Admin\ContentManager\AssetsPage\AssetsPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\AssetsPage\AssetsReferencesPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ReferencePage\ReferencePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\Pane\CustomContentPane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Pages\Element\Scald\AddOptionsModalBase;
use Kanooh\Paddle\Pages\Element\Scald\DeleteModal;
use Kanooh\Paddle\Pages\Element\Scald\LibraryModal;
use Kanooh\Paddle\Pages\Node\DeletePage\DeletePage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

/**
 * Base class for the LinkChecker tests.
 */
abstract class LinkCheckerTestBase extends WebDriverTestCase
{

    /**
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * @var AssetsPage
     */
    protected $assetsPage;

    /**
     * @var AssetsReferencesPage
     */
    protected $assetsReferencesPage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var SearchPage
     */
    protected $contentManagerPage;

    /**
     * @var DeletePage
     */
    protected $deletePage;

    /**
     * @var EditPage
     */
    protected $editPage;

    /**
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * @var ReferencePage
     */
    protected $referencePage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * Creates a node of the content type that is being tested.
     *
     * @param string $title
     *   Optional title for the node. If omitted a random title will be used.
     *
     * @return int
     *   The node ID of the node that was created.
     */
    abstract public function setupNode($title = null);

    /**
     * Get the 'Page layout' page belonging to a certain node type.
     *
     * @return LayoutPage
     *   The 'Page layout' page.
     */
    protected function getLayoutPage()
    {
        return new LayoutPage($this);
    }

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Instantiate some objects for later use.
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->assetCreationService = new AssetCreationService($this);
        $this->assetsPage = new AssetsPage($this);
        $this->contentManagerPage = new SearchPage($this);
        $this->deletePage = new DeletePage($this);
        $this->assetsReferencesPage = new AssetsReferencesPage($this);
        $this->editPage = new EditPage($this);
        $this->layoutPage = new LayoutPage($this);
        $this->referencePage = new ReferencePage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Go to the login page and log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Test the message appearing on the node archive form if the node is being
     * referenced.
     *
     * @group linkChecker
     */
    public function testNodeReferencedMessage()
    {
        // Create a node to be referenced.
        $referenced_title = $this->alphanumericTestDataProvider->getValidValue();
        $referenced_nid = $this->setupNode($referenced_title);

        // Ensure the reference message does not appear on nodes which are not
        // referenced.
        $this->administrativeNodeViewPage->go($referenced_nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonArchive->click();
        $modal = new ArchiveNodeModal($this);
        $modal->waitUntilOpened();
        $this->assertTextNotPresent('See the usage overview. Are you sure you want to break the links on');
        $modal->cancel();
        $this->administrativeNodeViewPage->checkArrival();

        // Create the node which will reference the first and add the reference.
        $referencing_nid = $this->setupNode();
        $this->editPage->go($referencing_nid);
        $this->editPage->body->waitUntilReady();
        $this->editPage->body->buttonSource->click();
        // Get a non-aliased link.
        $link = l($referenced_title, "node/$referenced_nid", array('alias' => true));
        $this->editPage->body->setBodyText($link);
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Try archiving the referenced node.
        $this->administrativeNodeViewPage->go($referenced_nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonArchive->click();
        $modal = new ArchiveNodeModal($this);
        $modal->waitUntilOpened();
        $message = 'This page is used by one page. See the usage overview. '
            . 'Are you sure you want to break the links on that page?';
        $this->assertTextPresent($message);
        $modal->cancel();
        $this->administrativeNodeViewPage->checkArrival();

        // Create another node referencing the first.
        $second_referencing_nid = $this->setupNode();
        $this->editPage->go($second_referencing_nid);
        $this->editPage->body->waitUntilReady();
        $this->editPage->body->buttonSource->click();
        // Get a non-aliased link.
        $link = l($referenced_title, "node/$referenced_nid", array('alias' => true));
        $this->editPage->body->setBodyText($link);
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Try archiving the referenced node again, to verify that the message
        // says that two pages are referencing it.
        $this->administrativeNodeViewPage->go($referenced_nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonArchive->click();
        $modal = new ArchiveNodeModal($this);
        $modal->waitUntilOpened();
        $message = 'This page is used by 2 pages. See the usage overview. '
            . 'Are you sure you want to break the links on those pages?';
        $this->assertTextPresent($message);
        $modal->cancel();
        $this->administrativeNodeViewPage->checkArrival();

        // Remove the references from the two nodes.
        foreach (array($referencing_nid, $second_referencing_nid) as $nid) {
            $this->editPage->go($nid);
            $this->editPage->body->waitUntilReady();
            $this->editPage->body->setBodyText('');
            $this->editPage->contextualToolbar->buttonSave->click();
            $this->administrativeNodeViewPage->checkArrival();
        }

        // Ensure the reference message does not appear anymore.
        $this->administrativeNodeViewPage->go($referenced_nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonArchive->click();
        $modal = new ArchiveNodeModal($this);
        $modal->waitUntilOpened();
        $this->assertTextNotPresent('See the usage overview. Are you sure you want to break the links on');
        $modal->confirm();
    }

    /**
     * Tests handling of atom references in the node body.
     *
     * @group linkChecker
     * @group wysiwyg
     */
    public function testAtomReferencesInNodeBody()
    {
        // Create the atoms for this test. We can use the ones for the Custom Pane test.
        $atoms = array(
            $this->assetCreationService->createImage(),
            $this->assetCreationService->createFile(),
            $this->assetCreationService->createVideo(),
        );

        // Create a node to put them into.
        $nid = $this->setupNode();

        // Add an atom to the body.
        $this->addAtomsToNodeBody($nid, array($atoms[0]['id']));

        // Check that record exists in the db for the reference.
        $this->assertStoredReferences('scald_atom', array($atoms[0]['id']), 'node', $nid);

        // Remove the atom and add the other ones.
        $this->addAtomsToNodeBody($nid, array($atoms[2]['id'], $atoms[1]['id']));

        // Check that the record for the deleted atom was deleted and that
        // a record exists in the db for the new ones.
        $this->assertStoredReferences('scald_atom', array($atoms[2]['id'], $atoms[1]['id']), 'node', $nid);

        // Publish the node. We do this to test if references in published nodes
        // are preserved.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Remove the atoms in a draft revision and add again a new atom to the
        // body. This should remove the reference for the second atom only but
        // keep the third since it's in the online version of the node.
        $this->addAtomsToNodeBody($nid, array($atoms[0]['id']));

        $this->assertStoredReferences('scald_atom', array($atoms[2]['id'], $atoms[0]['id']), 'node', $nid);

        // Verify that on the atom references page, the node is being shown.
        $node = node_load($nid);
        $this->assetsReferencesPage->go($atoms[2]['id']);

        // Get the human readable name of the bundle.
        $entity_info = entity_get_info('node');

        $this->assertTextPresent($node->title);
        $this->assertTextPresent($entity_info['bundles'][$node->type]['label']);

        // Finally delete the node and make sure all the atom references were
        // deleted.
        node_delete($nid);
        $this->assertEmpty(reference_tracker_get_outbound_references('node', $node->nid));

        $this->assetsReferencesPage->go($atoms[2]['id']);
        $this->assertTextNotPresent($node->title);
    }

    /**
     * Test the message appearing on the atom delete form if the atom is being
     * referenced.
     *
     * @group linkChecker
     */
    public function testAtomReferencedMessage()
    {
        // Create 3 atoms to be referenced.
        $atoms = array(
            $this->assetCreationService->createImage(),
            $this->assetCreationService->createVideo(),
            $this->assetCreationService->createFile(),
        );

        // Create a node to put them into.
        $nid = $this->setupNode();

        // Ensure the reference message does not appear on atoms which are not
        // referenced.
        $this->assetsPage->go();
        foreach ($atoms as $index => $atom) {
            $this->assetsPage->library->getAtomById($atom['id'])->deleteLink->click();
            $delete_modal = new DeleteModal($this);
            $delete_modal->waitUntilOpened();
            $atom = scald_atom_load($atom['id']);
            // Load the translated title for this type.
            $atoms[$index]['type'] = strtolower(scald_type_property_translate(scald_type_load($atom->type)));
            $this->assertTextNotPresent('This ' . $atoms[$index]['type'] . ' is used by');
            $this->assertTextNotPresent('See the usage overview. Are you sure you want to break the links on');
            $delete_modal->close();
            $delete_modal->waitUntilClosed();
        }

        // Add all the atoms to the node.
        $atom_ids = array_column($atoms, 'id');
        $this->addAtomsToNodeBody($nid, $atom_ids);

        // Go to the media library page and verify the message.
        $this->assetsPage->go();
        foreach ($atoms as $atom) {
            $this->assetsPage->library->getAtomById($atom['id'])->deleteLink->click();
            $delete_modal = new DeleteModal($this);
            $delete_modal->waitUntilOpened();
            $message = 'This ' . $atom['type'] . ' is used by one page. See the usage overview. '
              . 'Are you sure you want to break the links on that page?';
            $this->assertTextPresent($message);
            $delete_modal->form->cancelButton->click();
            $delete_modal->waitUntilClosed();
        }

        // Create another node and add again references.
        $second_nid = $this->setupNode();
        $this->addAtomsToNodeBody($second_nid, $atom_ids);

        // Verify that the message now reports two differences.
        $this->assetsPage->go();
        foreach ($atoms as $atom) {
            $this->assetsPage->library->getAtomById($atom['id'])->deleteLink->click();
            $delete_modal = new DeleteModal($this);
            $delete_modal->waitUntilOpened();
            $message = 'This ' . $atom['type'] . ' is used by 2 pages. See the usage overview. '
                . 'Are you sure you want to break the links on those pages?';
            $this->assertTextPresent($message);
            $delete_modal->form->deleteButton->click();
            $delete_modal->waitUntilClosed();
        }
    }

    /**
     * Test the warning message displayed when bulk archiving referenced nodes.
     *
     * @group linkChecker
     */
    public function testReferenceWarningWhenBulkArchiving()
    {
        // Create a few nodes to be referenced.
        $referenced_nodes = array();
        for ($i = 0; $i < 3; $i++) {
            $title = $this->alphanumericTestDataProvider->getValidValue();
            $referenced_nodes[$title] = $this->setupNode($title);
        }
        // Create a non-referenced node which will hold the references.
        $referencing_nid = $this->setupNode();

        // Add references to the referenced nodes.
        $this->editPage->go($referencing_nid);
        $this->editPage->body->waitUntilReady();
        $this->editPage->body->buttonSource->click();
        $text = '';
        foreach ($referenced_nodes as $title => $nid) {
            $text .= l($title, "node/$nid", array('alias' => true));
        }
        $this->editPage->body->setBodyText($text);
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Try to bulk archive all of the nodes.
        $this->contentManagerPage->go();
        foreach ($referenced_nodes as $title => $nid) {
            $row = $this->contentManagerPage->contentTable->getNodeRowByNid($nid);
            $row->bulkActionCheckbox->check();
        }
        $this->contentManagerPage->bulkActions->selectAction->selectOptionByLabel('Set moderation state');
        $this->contentManagerPage->bulkActions->selectState->selectOptionByLabel('Archived');
        $this->contentManagerPage->bulkActions->executeButton->click();
        $this->contentManagerPage->checkArrival();

        // Check that there is a warning message is it contains the correct links.
        $warning_message = 'The following pages are used by other pages. See the usage overview below. '
          . 'Are you sure you want to break the links on those pages?';
        $this->assertTextPresent($warning_message);
        $this->assertCount(3, $this->contentManagerPage->referencedNodesWarningLinks);
        foreach ($this->contentManagerPage->referencedNodesWarningLinks as $link) {
            $nid = $referenced_nodes[$link->text()];
            $this->assertNotNull($nid);
            $url = url("node/$nid/references", array('absolute' => true));
            $this->assertEquals($url, $link->attribute('href'));
        }

        // Archive all the referenced nodes.
        $this->contentManagerPage->bulkActions->buttonConfirm->click();
        $this->contentManagerPage->checkArrival();

        // Try archiving the non-referenced node and make sure there is no
        // warning message.
        $row = $this->contentManagerPage->contentTable->getNodeRowByNid($referencing_nid);
        $row->bulkActionCheckbox->check();
        $this->contentManagerPage->bulkActions->selectAction->selectOptionByLabel('Set moderation state');
        $this->contentManagerPage->bulkActions->selectState->selectOptionByLabel('Archived');
        $this->contentManagerPage->bulkActions->executeButton->click();
        $this->contentManagerPage->checkArrival();
        $this->assertTextNotPresent($warning_message);
        $this->assertEmpty($this->contentManagerPage->referencedNodesWarningLinks);
    }

    /**
     * Tests that references between nodes in custom content panes are tracked.
     *
     * @group linkChecker
     */
    public function testNodeReferencesInCustomContentPane()
    {
        // Create a node to be referenced.
        $referenced_title = $this->alphanumericTestDataProvider->getValidValue();
        $referenced_nid = $this->setupNode($referenced_title);

        // Create the node which will reference the first.
        $referencing_nid = $this->setupNode();

        // Add a custom content pane in a region.
        $this->layoutPage->go($referencing_nid);
        $region = $this->layoutPage->display->getRandomRegion();
        $content_type = new CustomContentPanelsContentType($this);
        $callable = new SerializableClosure(
            function () use ($content_type, $referenced_title, $referenced_nid) {
                $content_type->getForm()->body->waitUntilReady();
                // Get a non-aliased link.
                $link = l($referenced_title, "node/$referenced_nid", array('alias' => true));
                $content_type->getForm()->body->setBodyText($link);
            }
        );
        $pane = $region->addPane($content_type, $callable);
        $pane_uuid = $pane->getUuid();

        // Save the layout page.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Verify that the reference is in place.
        $this->assertStoredReferences('node', array($referenced_nid), 'node', $referencing_nid);

        // Edit the pane and remove the reference.
        $this->layoutPage->go($referencing_nid);
        $pane = new CustomContentPane($this, $pane_uuid, '//div[@data-pane-uuid="' . $pane_uuid . '"]');
        $pane->toolbar->buttonEdit->click();
        $pane->editPaneModal->waitUntilOpened();
        $content_type = new CustomContentPanelsContentType($this);
        $content_type->getForm()->body->waitUntilReady();
        $content_type->getForm()->body->setBodyText('');
        $pane->editPaneModal->submit();
        $pane->editPaneModal->waitUntilClosed();

        // Save the layout page.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Verify that the reference has been removed.
        $this->assertStoredReferences(false, array(), 'node', $referencing_nid);
    }

    /**
     * Tests that references between nodes in custom content panes are tracked.
     *
     * @group linkChecker
     */
    public function testAtomReferencesInCustomContentPane()
    {
        // Create an atom to be referenced.
        $atom = $this->assetCreationService->createImage();

        // Create the node which will reference the first.
        $referencing_nid = $this->setupNode();

        // Add a custom content pane in a region.
        $this->layoutPage->go($referencing_nid);
        $region = $this->layoutPage->display->getRandomRegion();
        $content_type = new CustomContentPanelsContentType($this);
        $test_case = $this;
        $callable = new SerializableClosure(
            function () use ($test_case, $content_type, $atom) {
                $content_type->getForm()->body->waitUntilReady();
                $content_type->getForm()->body->buttonOpenScaldLibraryModal->click();
                $library_modal = new LibraryModal($test_case);
                $library_modal->waitUntilOpened();
                $library_modal->library->getAtomById($atom['id'])->insertLink->click();
                $library_modal->waitUntilClosed();
            }
        );
        $pane = $region->addPane($content_type, $callable);

        // Save the layout page.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Verify that the reference is in place.
        $this->assertStoredReferences('atom', array($atom['id']), 'node', $referencing_nid);

        // Edit the pane and remove the reference.
        $this->layoutPage->go($referencing_nid);
        $pane = new CustomContentPane($this, $pane->getUuid(), $pane->getXPathSelectorByUuid());
        $pane->toolbar->buttonEdit->click();
        $pane->editPaneModal->waitUntilOpened();
        $content_type = new CustomContentPanelsContentType($this);
        $content_type->getForm()->body->waitUntilReady();
        $content_type->getForm()->body->setBodyText('');
        $pane->editPaneModal->submit();
        $pane->editPaneModal->waitUntilClosed();

        // Save the layout page.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Verify that the reference has been removed.
        $this->assertStoredReferences(false, array(), 'node', $referencing_nid);
    }

    /**
     * Tests that references message appears on the atom options modal.
     *
     * @group linkChecker
     */
    public function testReferencesMessageInAtomOptionsModal()
    {
        // Create 4 atoms to be referenced.
        $atoms = array(
            $this->assetCreationService->createImage(),
            $this->assetCreationService->createVideo(),
            $this->assetCreationService->createYoutubeVideo(),
            $this->assetCreationService->createFile(),
        );

        // Create a node to put them into.
        $nid = $this->setupNode();

        // Ensure the reference message does not appear on atoms which are not
        // referenced.
        $this->assetsPage->go();
        foreach ($atoms as $index => $atom) {
            $this->assetsPage->library->getAtomById($atom['id'])->editLink->click();
            $options_modal = new AddOptionsModalBase($this);
            $options_modal->waitUntilOpened();
            $atom = scald_atom_load($atom['id']);
            // Load the translated title for this type.
            $atoms[$index]['type'] = strtolower(scald_type_property_translate(scald_type_load($atom->type)));
            $this->assertTextNotPresent('This ' . $atoms[$index]['type'] . ' is used by');
            $this->assertTextNotPresent('See the usage overview.');
            $options_modal->close();
            $options_modal->waitUntilClosed();
        }

        // Add all the atoms to the node.
        $atom_ids = array_column($atoms, 'id');
        $this->addAtomsToNodeBody($nid, $atom_ids);

        // Go to the media library page and try editing each atom in turn. They
        // should have the warning message.
        $this->assetsPage->go();
        foreach ($atoms as $atom) {
            $this->assetsPage->library->getAtomById($atom['id'])->editLink->click();
            $options_modal = new AddOptionsModalBase($this);
            $options_modal->waitUntilOpened();
            $this->assertTextPresent('This ' . $atom['type'] . ' is used by one page. See the usage overview.');
            $options_modal->close();
            $options_modal->waitUntilClosed();
        }

        // Create another node and add again references.
        $second_nid = $this->setupNode();
        $this->addAtomsToNodeBody($second_nid, $atom_ids);

        // Verify that the message now changes to report 2 pages.
        $this->assetsPage->go();
        foreach ($atoms as $atom) {
            $this->assetsPage->library->getAtomById($atom['id'])->editLink->click();
            $options_modal = new AddOptionsModalBase($this);
            $options_modal->waitUntilOpened();
            $this->assertTextPresent('This ' . $atom['type'] . ' is used by 2 pages. See the usage overview.');
            $options_modal->close();
            $options_modal->waitUntilClosed();
        }
    }

    /**
     * Tests that no unwanted messages are shown on the NodeReferencePage.
     *
     * @group linkChecker
     */
    public function testRemovalOfUnwantedMessages()
    {
        // Create the first Basic page and assign another user to it.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $referenced_nid = $this->contentCreationService->createBasicPage($title);
        $this->editPage->go($referenced_nid);
        $this->editPage->responsibleAuthor->fill('demo_editor');

        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element $element */
        $autocomplete = new AutoComplete($this);
        $autocomplete->pickSuggestionByPosition(0);
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Create a second Basic page which has a referenced link to the first one.
        $referencing_nid = $this->contentCreationService->createBasicPage();
        $this->editPage->go($referencing_nid);
        $this->editPage->body->buttonLink->click();

        $modal = $this->editPage->body->modalLink;
        $modal->waitUntilOpened();
        $modal->linkInfoForm->link->fill($title);

        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilSuggestionCountEquals(1);

        // Use the arrow keys to select the result, and press enter to confirm.
        $this->keys(Keys::DOWN . Keys::ENTER);

        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Browse to the ReferencePage of the first Basic Page.
        // Assert that the following message is not shown.
        $this->referencePage->go($referenced_nid);
        $this->assertTextNotPresent("A page an user is responsible for has been modified");
    }

    /**
     * Asserts that the stored references for an entity are what we expect them to be.
     *
     * @param string $expected_type
     *   The expected type of the stored references.
     * @param array $expected_ids
     *   Array with the expected ids of the stored references.
     * @param string $entity_type
     *   The type of the entity which holds the references.
     * @param int $entity_id
     *   The id of the entity which holds the references.
     */
    protected function assertStoredReferences($expected_type, $expected_ids, $entity_type, $entity_id)
    {
        $expected_references = array($expected_type => array());
        foreach ($expected_ids as $id) {
            // Convert the id to string to make the comparison possible.
            $expected_references[$expected_type][] = $id;
        }
        $actual = asort(reference_tracker_get_outbound_references($entity_type, $entity_id));
        $this->assertSame(asort($expected_references), $actual);
    }

    /**
     * Adds the passed atoms to the body of the node passed.
     *
     * The method will clear the body before adding the atoms to it.
     *
     * @param string $nid
     *   The id of the node to add the atoms to.
     * @param array $atom_ids
     *   Array containing the IDs of the atoms we need to add to the pane.
     */
    protected function addAtomsToNodeBody($nid, $atom_ids)
    {
        $this->editPage->go($nid);
        $this->editPage->body->waitUntilReady();
        $this->editPage->body->setBodyText('');
        foreach ($atom_ids as $id) {
            $this->editPage->body->buttonOpenScaldLibraryModal->click();
            $library_modal = new LibraryModal($this);
            $library_modal->waitUntilOpened();
            $library_modal->library->getAtomById($id)->insertLink->click();
            $library_modal->waitUntilClosed();
        }

        // Save the node.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        AssetCreationService::cleanUp($this);

        parent::tearDown();
    }
}
