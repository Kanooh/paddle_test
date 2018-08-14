<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Search\Base\AttachmentSearchTestBase.
 */

namespace Kanooh\Paddle\Core\Search\Base;

use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Element\Scald\AddAssetModal;
use Kanooh\Paddle\Pages\Element\Scald\AddAtomModal;
use Kanooh\Paddle\Pages\Element\Scald\Document\AddOptionsModal;
use Kanooh\Paddle\Pages\Element\Scald\LibraryModal;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage as NodeEditPage;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalApi\DrupalSearchApiApi;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the search functionality.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
abstract class AttachmentSearchTestBase extends WebDriverTestCase
{

    /**
     * @var ViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var DrupalSearchApiApi
     */
    protected $drupalSearchApiApi;

    /**
     * @var NodeEditPage
     */
    protected $nodeEditPage;

    /**
     * @var array
     */
    protected $nodes = array();

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * Search for a certain string.
     *
     * @param string
     *   The text to search for on the search page.
     */
    abstract public function searchFor($keyword);

    /**
     * Gets the search results.
     *
     * @return \Kanooh\Paddle\Pages\SearchPage\SearchResults
     */
    abstract public function getSearchResults();

  /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        $this->administrativeNodeViewPage = new ViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->drupalSearchApiApi = new DrupalSearchApiApi($this);
        $this->nodeEditPage = new NodeEditPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Tests the search on file content.
     *
     * @group search
     */
    public function testAttachmentSearch()
    {
        // Remove all the atoms.
        $clean_up_service = new CleanUpService($this);
        $clean_up_service->deleteEntities('scald_atom');
        $clean_up_service->deleteEntities('node');

        // Create a basic page.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $nid = $this->contentCreationService->createBasicPage($title);

        // Go to the edit page and add a PDF to the body.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->body->waitUntilReady();
        $this->nodeEditPage->body->buttonOpenScaldLibraryModal->click();
        $library_modal = new LibraryModal($this);
        $library_modal->waitUntilOpened();
        // Add a new file.
        $library_modal->addAssetButton->click();
        $add_asset_modal = new AddAssetModal($this);
        $add_asset_modal->waitUntilOpened();
        $add_asset_modal->fileLink->click();
        $add_document_modal = new AddAtomModal($this);
        $add_document_modal->waitUntilOpened();

        $doc_path = dirname(__FILE__) . '/../../../assets/pdf-sample.pdf';
        $title = basename($doc_path);
        $document = $this->file($doc_path);

        $add_document_modal->form->fileList->uploadFiles($document);

        $options_modal = new AddOptionsModal($this);
        $options_modal->waitUntilOpened();
        $options_modal->form->finishButton->click();
        // Wait until the library is reloaded.
        $library_modal->waitUntilOpened();
        $this->waitUntilTextIsPresent('Atom ' . $title . ', of type File has been created.');

        $atom = $library_modal->library->items[0];

        // Insert the atom in the CKEditor.
        $atom->insertLink->click();
        $library_modal->waitUntilClosed();

        // Save and publish the page.
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Index all the nodes and commit the index itself.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        $this->searchFor('Acrobat');
        $this->assertTextPresent($title);
        $search_results = $this->getSearchResults();
        $this->assertCount(1, $search_results->getResults());
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->contentCreationService->cleanUp($this);
        parent::tearDown();
    }
}
