<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\Base\NodeDeleteButtonTestBase.
 */

namespace Kanooh\Paddle\Core\ContentType\Base;

use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\ArchivePage\ArchivePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\ArchivePage\ArchivePageContentTableRow;
use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPageContentTableRow;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminNodeViewPage;
use Kanooh\Paddle\Pages\Node\DeletePage\DeletePage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Test for the node delete functionality.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
abstract class NodeDeleteButtonTestBase extends WebDriverTestCase
{

    /**
     * @var AddPage
     */
    protected $addContentPage;

    /**
     * @var AdminNodeViewPage
     */
    protected $adminNodeViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var ArchivePage
     */
    protected $archivePage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var DeletePage
     */
    protected $deletePage;

    /**
     * @var EditPage
     */
    protected $editPage;

    /**
     * @var SearchPage
     */
    protected $searchPage;

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
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Instantiate the Pages that will be visited in the test.
        $this->addContentPage = new AddPage($this);
        $this->adminNodeViewPage = new AdminNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->archivePage = new ArchivePage($this);
        $this->deletePage = new DeletePage($this);
        $this->editPage = new EditPage($this);
        $this->searchPage = new SearchPage($this);

        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
    }

    /**
     * Dataprovider for tests that should be run on multiple user roles.
     */
    public function usersDataProvider()
    {
        return array(
          array('Editor'),
          array('ChiefEditor'),
        );
    }

    /**
     * Test if the "Delete" button works on the administrative node view.
     *
     * It should redirect to the content search view.
     *
     * @dataProvider usersDataProvider
     *
     * @group contentType
     * @group nodeDeleteButtonTestBase
     * @group workflow
     */
    public function testNodeViewDeleteButton($user)
    {
        $this->userSessionService->login($user);

        // Setup the node and go to the backend node view.
        $nid = $this->setupNode();
        $this->adminNodeViewPage->go($nid);
        $this->adminNodeViewPage->contextualToolbar->checkButtonsNotPresent(array('Delete'));

        // Click the delete button in the contextual toolbar.
        $this->contentCreationService->moderateNode($nid, 'archived');
        $this->adminNodeViewPage->go($nid);
        $this->adminNodeViewPage->checkArrival();
        $this->adminNodeViewPage->contextualToolbar->buttonDelete->click();
        $this->deletePage->checkArrival();
        $this->assertFalse($this->deletePage->checkClassPresent('paddle-social-media-share-button'));

        // Cancel deletion.
        $this->deletePage->buttonCancel->click();
        $this->adminNodeViewPage->checkArrival();

        // This time confirm the deletion.
        $this->adminNodeViewPage->contextualToolbar->buttonDelete->click();
        $this->deletePage->checkArrival();
        $this->deletePage->buttonConfirm->click();
        $this->archivePage->checkArrival();
    }

    /**
     * Test if the "Delete" button works on the archive overview.
     *
     * It should redirect to the archive search view.
     *
     * @dataProvider usersDataProvider
     *
     * @group contentType
     * @group nodeDeleteButtonTestBase
     * @group workflow
     */
    public function testArchiveOverviewDeleteButton($user)
    {
        $this->userSessionService->login($user);

        // Setup the node and go to the backend node view.
        $nid = $this->setupNode();
        $this->contentCreationService->moderateNode($nid, 'archived');

        // Verify that after moderation to the archived state the delete
        // button is shown on the archive page.
        $this->archivePage->go();
        /** @var ArchivePageContentTableRow $row */
        $row = $this->archivePage->contentTable->getNodeRowByNid($nid);
        $row->links->linkDelete->click();
        $this->deletePage->checkArrival();

        // Confirm the deletion.
        $this->deletePage->buttonConfirm->click();
        $this->archivePage->checkArrival();
    }

    /**
     * Test if the "Delete" button works on the content manager overview.
     *
     * It should redirect to the content search view.
     *
     * @dataProvider usersDataProvider
     *
     * @group contentType
     * @group nodeDeleteButtonTestBase
     * @group workflow
     */
    public function testContentManagerOverviewDeleteButton($user)
    {
        $this->userSessionService->login($user);

        // Setup the node.
        $nid = $this->setupNode();

        // Go the search page and verify you cannot delete the node.
        $this->searchPage->go();
        /** @var SearchPageContentTableRow $row */
        $row = $this->searchPage->contentTable->getNodeRowByNid($nid);
        $row->links->checkLinksNotPresent(array('Delete'));
    }

    /**
     * Test if the "Delete" bulk actions works on the archive overview.
     *
     * It should redirect to the archive overview.
     *
     * @dataProvider usersDataProvider
     *
     * @group contentType
     * @group nodeDeleteButtonTestBase
     * @group workflow
     */
    public function testArchivePageBulkActionDelete($user)
    {
        $this->userSessionService->login($user);

        // Setup the node and go to the backend node view.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $nid = $this->setupNode($title);
        $this->contentCreationService->moderateNode($nid, 'archived');

        // Verify the delete bulk action works on the archive overview.
        $this->archivePage->go();
        /** @var ArchivePageContentTableRow $row */
        $row = $this->archivePage->contentTable->getNodeRowByNid($nid);
        $row->bulkActionCheckbox->check();
        $this->archivePage->bulkActions->selectAction->selectOptionByLabel('Delete item');
        $this->archivePage->bulkActions->executeButton->click();
        $this->archivePage->checkArrival();
        $this->assertTextPresent($title);
        $this->archivePage->bulkActions->buttonConfirm->click();
        $this->archivePage->checkArrival();

        // Now do the test for multiple archived nodes.
        for ($i=0; $i < 2; $i++) {
            $nid = $this->setupNode($title);
            $this->contentCreationService->moderateNode($nid, 'archived');
        }

        // Verify the delete bulk action works on the archive overview for
        // multiple nodes.
        $this->archivePage->go();
        $this->assertTrue(count($this->archivePage->contentTable->rows) > 0);
        /** @var ArchivePageContentTableRow $row */
        $this->archivePage->bulkActions->selectAll->check();
        $this->archivePage->bulkActions->selectAction->selectOptionByLabel('Delete item');
        $this->archivePage->bulkActions->executeButton->click();
        $this->archivePage->checkArrival();
        $this->archivePage->bulkActions->buttonConfirm->click();
        $this->archivePage->checkArrival();
        $this->assertFalse($this->archivePage->contentTable->isPresent());
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
