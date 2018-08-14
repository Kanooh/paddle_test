<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentManager\ContentManagerSearchTest.
 */

namespace Kanooh\Paddle\Core\ContentManager;

use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPage;
use Kanooh\Paddle\Pages\Admin\DashboardPage\DashboardPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\WebDriver\WebDriverTestCase;
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

/**
 * Performs tests on the Paddle Content Manager search.
 *
 * @package Kanooh\Paddle\Core\ContentManager
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ContentManagerSearchTest extends WebDriverTestCase
{
    /**
     * Test data provider for alphanumeric data.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The front-end view of a landing page.
     *
     * @var FrontPage
     */
    protected $frontendPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * The content management search page.
     * @var SearchPage
     */
    protected $searchPage;

    /**
     * The dashboard page.
     *
     * @var DashboardPage
     */
    protected $dashboardPage;

    /**
     * The Add Content page.
     *
     * @var AddPage
     */
    protected $addContentPage;

    /**
     * The Administrative node View page.
     * @var ViewPage
     */
    protected $adminNodeViewPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->addContentPage = new AddPage($this);
        $this->adminNodeViewPage = new ViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->dashboardPage = new DashboardPage($this);
        $this->frontendPage = new FrontPage($this);
        $this->searchPage = new SearchPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as an editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Tests the search form in the header.
     *
     * @group workflow
     */
    public function testBackEndHeaderSearch()
    {
          // Create 2 nodes with 2 different titles.
          $node_title_1 = $this->alphanumericTestDataProvider->getValidValue();
          $node_title_2 = $this->alphanumericTestDataProvider->getValidValue();
          $nid_1 = $this->contentCreationService->createBasicPage($node_title_1);
          $nid_2 = $this->contentCreationService->createBasicPage($node_title_2);

          // Go to the dashboard and verify the search box is present.
          $this->dashboardPage->go();
          $this->assertTrue($this->dashboardPage->searchBox->isPresent());

          // Fill out a title of one of the created nodes and verify you land on
          // the content manager search page with the correct page in the list.
          $this->dashboardPage->searchBox->form->searchField->fill($node_title_1);
          // Simulate the user using the keyboard to search.
          $this->keys(Keys::ENTER);
          $this->searchPage->checkArrival();

          $node_row = $this->searchPage->contentTable->getNodeRowByNid($nid_1);
          $this->assertTrue($node_row->isPresent());

          $node_row = $this->searchPage->contentTable->getNodeRowByNid($nid_2);
          $this->assertFalse($node_row);

          // Now search for the other node and verify the correct node is shown
          // in the table.
          $this->searchPage->searchBox->form->searchField->fill($node_title_2);
          // Simulate the user using the keyboard to search.
          $this->keys(Keys::ENTER);
          $this->searchPage->checkArrival();

          $node_row = $this->searchPage->contentTable->getNodeRowByNid($nid_2);
          $this->assertTrue($node_row->isPresent());
          $node_row = $this->searchPage->contentTable->getNodeRowByNid($nid_1);
          $this->assertFalse($node_row);

          // Now search for a random string and verify none of the nodes is
          // shown in the table.
          $this->searchPage->searchBox->form->searchField->fill($this->alphanumericTestDataProvider->getValidValue());
          // Simulate the user using the keyboard to search.
          $this->keys(Keys::ENTER);
          $this->searchPage->checkArrival();

          $node_row = $this->searchPage->contentTable->getNodeRowByNid($nid_1);
          $this->assertFalse($node_row);
          $node_row = $this->searchPage->contentTable->getNodeRowByNid($nid_2);
          $this->assertFalse($node_row);

          // Check that the search box is not shown on the front end.
          $this->frontendPage->go();
          $this->assertContentSearchBoxNotPresent();
    }

    /**
     * Asserts that the content search box is not present.
     */
    protected function assertContentSearchBoxNotPresent()
    {
        $elements = $this->elements($this->using('xpath')->value('//div[@id="block-paddle-core-content-search"]'));
        $present = (bool) count($elements);
        $this->assertFalse($present);
    }
}
