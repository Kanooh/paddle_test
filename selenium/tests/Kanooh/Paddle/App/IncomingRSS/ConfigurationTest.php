<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\IncomingRSS\ConfigurationTest
 */

namespace Kanooh\Paddle\App\IncomingRSS;

use Kanooh\Paddle\Apps\IncomingRSS;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleIncomingRSS\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Element\IncomingRSS\RSSFeedDeleteModal;
use Kanooh\Paddle\Pages\Element\IncomingRSS\RSSFeedModal;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs configuration tests on the Incoming RSS paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ConfigurationTest extends WebDriverTestCase
{
    /**
     * The alphanumeric test data generator.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * App service.
     *
     * @var AppService
     */
    protected $appService;

    /**
     * The paddlet configuration page.
     *
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * User session service.
     *
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Instantiate some classes to use in the test.
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->configurePage = new ConfigurePage($this);
        $this->userSessionService = new UserSessionService($this);

        // Log in as site manager.
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new IncomingRSS);
    }

    /**
     * Tests the configuration of the paddlet.
     */
    public function testConfiguration()
    {
        $this->configurePage->go();
        $this->configurePage->checkArrival();

        // Create a new RSS feed.
        $this->configurePage->contextualToolbar->buttonAdd->click();
        $modal = new RSSFeedModal($this);
        $modal->waitUntilOpened();

        // Verify that the elements are required.
        $modal->form->saveButton->click();
        $this->waitUntilTextIsPresent('Title field is required.');
        $this->waitUntilTextIsPresent('URL field is required.');

        // Create a new feed.
        $feed_title = $this->alphanumericTestDataProvider->getValidValue();
        $modal->form->title->fill($feed_title);

        // First enter an invalid RSS source URL to see if the validation works.
        $modal->form->url->fill('sadda');
        $modal->form->saveButton->click();
        $this->waitUntilTextIsPresent('The URL is not a valid RSS source.');

        // Then make sure that even if a valid URL is entered, a check that
        // it contains RSS is being done.
        $modal->close();
        $modal->waitUntilClosed();
        $this->configurePage->checkArrival();
        $this->configurePage->contextualToolbar->buttonAdd->click();
        $modal = new RSSFeedModal($this);
        $modal->waitUntilOpened();
        $modal->form->title->fill($feed_title);
        $modal->form->url->fill('http://google.com');
        $modal->form->saveButton->click();
        $this->waitUntilTextIsPresent('The URL is not a valid RSS source.');

        // Now enter a valid value.
        $modal->form->url->fill('http://feeds.bbci.co.uk/news/rss.xml');

        $modal->form->saveButton->click();
        $modal->waitUntilClosed();

        // Assert that the message is shown and our new feed is in place.
        $this->waitUntilTextIsPresent('RSS feed saved.');
        $row = $this->configurePage->feedTable->getRowByTitle($feed_title);
        $this->assertNotNull($row);

        // Try deleting the row but cancel it.
        $row->linkDelete->click();
        $delete_modal = new RSSFeedDeleteModal($this);
        $delete_modal->waitUntilOpened();
        $delete_modal->buttonCancel->click();
        $delete_modal->waitUntilClosed();

        $this->configurePage->checkArrival();

        // The feed should not be deleted.
        $row = $this->configurePage->feedTable->getRowByTitle($feed_title);
        $this->assertNotNull($row);

        // Now delete if for real.
        $row->linkDelete->click();
        $delete_modal = new RSSFeedDeleteModal($this);
        $delete_modal->waitUntilOpened();
        $delete_modal->buttonConfirm->click();
        $delete_modal->waitUntilClosed();
        $this->assertFalse($this->configurePage->feedTable->getRowByTitle($feed_title));
    }
}
