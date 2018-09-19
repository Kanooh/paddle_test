<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Admin\Base\NoSearchResultsMessageTestBase.
 */

namespace Kanooh\Paddle\Core\Admin\Base;

use Kanooh\Paddle\Pages\Admin\SiteSettings\SiteSettingsPage;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalApi\DrupalSearchApiApi;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\EmailTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for the no search results message tests.
 */
abstract class NoSearchResultsMessageTestBase extends WebDriverTestCase
{

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var EmailTestDataProvider
     */
    protected $emailTestDataProvider;

    /**
     * @var SiteSettingsPage
     */
    protected $siteSettingsPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * Creates a node of the content type that is being tested.
     *
     * @return int
     *   The node ID of the node that was created.
     */
    abstract public function setupNode();

    /**
     * Execute a search on the search page that gives no results.
     *
     * @param int $search_page_nid
     *   Id of the search page to go to.
     * @param string $search_string
     *   The string to search for.
     */
    abstract public function searchForSomethingThatReturnsNoResults($search_page_nid, $search_string);

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Instantiate some objects for later use.
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->siteSettingsPage = new SiteSettingsPage($this);
        $this->emailTestDataProvider = new EmailTestDataProvider();
    }

    /**
     * Tests the custom 'No search results' message.
     *
     * @group noSearchResultsMessageTestBase
     * @group search
     */
    public function testCustomNoResultsMessage()
    {
        // Remove all nodes.
        $clean_up_service = new CleanUpService($this);
        $clean_up_service->deleteEntities('node', false, array(), array('paddle_overview_page'));

        // Reindex the node index so you are sure there are no more results possible.
        $drupalSearchApiApi = new DrupalSearchApiApi($this);
        $drupalSearchApiApi->indexItems('node_index');
        $drupalSearchApiApi->commitIndex('node_index');

        $this->userSessionService->login('ChiefEditor');

        // Search for something random in a newly created search page.
        $nid = $this->setupNode();
        $this->searchForSomethingThatReturnsNoResults($nid, 'Something random');

        // Assert the default search results message.
        $default_text = paddle_search_retrieve_default_no_search_results_message();
        $default_format = paddle_search_retrieve_no_search_results_message_format();
        // Split up the list elements since we can only check on text values.
        preg_match_all('(<(li|h2)>(.*)</(li|h2)>)', check_markup($default_text, $default_format), $output);

        foreach ($output[2] as $text_element) {
            $this->assertTextPresent(strip_tags($text_element));
        }

        // Change the search results message.
        $this->userSessionService->switchUser('SiteManager');
        $this->siteSettingsPage->go();
        $custom_text = 'Custom no results message.';
        // You are required to set the e-mail in the Site Settings page. It might be not filled in.
        $email = $this->emailTestDataProvider->getValidValue();
        $this->siteSettingsPage->siteEmail->fill($email);
        // Set the No Search Results message.
        $this->siteSettingsPage->noSearchResultsMessage->setBodyText($custom_text);
        $this->siteSettingsPage->contextualToolbar->buttonSave->click();
        $this->siteSettingsPage->checkArrival();

        // Assert the new search results message after searching something random.
        $this->searchForSomethingThatReturnsNoResults($nid, 'Something random');
        $this->assertTextPresent($custom_text);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Restore the default message by removing the custom one.
        variable_delete('paddle_no_results_on_search');
    }
}
