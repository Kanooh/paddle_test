<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OrganizationalUnit\OrganizationalUnitSearchTest.
 */

namespace Kanooh\Paddle\Core\Search;

use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditOrganizationalUnitPage;
use Kanooh\Paddle\Pages\SearchPage\PaddleSearchPage;
use Kanooh\Paddle\Utilities\AppService;
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
class OrganizationalUnitSearchTest extends WebDriverTestCase
{
    /**
     * @var ViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var AppService
     */
    protected $appService;

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
     * @var EditOrganizationalUnitPage
     */
    protected $nodeEditPage;

    /**
     * @var PaddleSearchPage
     */
    protected $searchPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        $this->administrativeNodeViewPage = new ViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->drupalSearchApiApi = new DrupalSearchApiApi($this);
        $this->nodeEditPage = new EditOrganizationalUnitPage($this);
        $this->searchPage = new PaddleSearchPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Tests that all node fields are searchable by checking one which is
     * usually not searchable.
     *
     * @group organizationalUnit
     * @group search
     */
    public function testSearchAnyField()
    {
        // Define a word to search on.
        $word = $this->alphanumericTestDataProvider->getValidValue();
        $title = $this->alphanumericTestDataProvider->getValidValue();

        // Create an organizational unit.
        $nid = $this->contentCreationService->createOrganizationalUnit($title);
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->locationName->fill($word);
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Index all the nodes and commit the index itself.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Search for the common word.
        $this->searchPage->go();
        $this->searchPage->searchForm->keywords->fill($word);
        $this->searchPage->searchForm->submit->click();
        $this->searchPage->checkArrival();

        // Check that we have our node.
        $results = $this->searchPage->searchResults->getResults();
        $this->assertCount(1, $results);

        // Reorganize the results by title, as it's safe.
        $results_by_title = array();
        foreach ($results as $result) {
            $results_by_title[$result->title] = $result;
        }
        $result = array_pop($results);
        $this->assertEquals($title, $result->title);

        // Clean up test data.
        node_delete_multiple(array($nid));
    }
}
