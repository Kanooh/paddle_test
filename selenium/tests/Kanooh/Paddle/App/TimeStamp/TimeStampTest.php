<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\TimeStamp\TimeStampTest.
 */

namespace Kanooh\Paddle\App\TimeStamp;

use Kanooh\Paddle\Apps\TimeStamp;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleTimeStamp\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndViewPage;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\WebDriver\WebDriverTestCase;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;

/**
 * Performs Timestamp tests.
 *
 * @package Kanooh\Paddle\App\Timestamp
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class TimeStampTest extends WebDriverTestCase
{
    /**
     * @var ViewPage
     */
    protected $adminViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var FrontEndViewPage
     */
    protected $frontEndViewPage;

    /**
     * @var EditPage
     */
    protected $nodeEditPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Prepare some variables for later use.
        $this->adminViewPage = new ViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->configurePage = new ConfigurePage($this);
        $this->frontEndViewPage = new FrontEndViewPage($this);
        $this->nodeEditPage = new EditPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as a site manager.
        $this->userSessionService->login('SiteManager');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new TimeStamp);
    }

    /**
     * Tests if the timestamp field value is visible on frontend.
     *
     * @group Timestamp
     */
    public function testTimestampFrontEnd()
    {
        // Create a landing page.
        $nid = $this->contentCreationService->createLandingPage();

        // Go to the configuration page and enable the checkbox for this content type.
        $this->configurePage->go();
        $this->configurePage->configureForm->typeLandingPage->check();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');

        // Publish the page.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();
        $this->adminViewPage->contextualToolbar->buttonPublish->click();

        // Verify that the timestamp div is present.
        $this->frontEndViewPage->go($nid);
        $xpath = '//div[contains(@class,"region-content")]/div[contains(@class, "field-name-field-paddle-timestamp")]';
        $this->waitUntilElementIsPresent($xpath);

        // Create a basic page (the difference is that basic page is panelized).
        $page_nid = $this->contentCreationService->createBasicPage();

        // Go to the configuration page and enable the checkbox for this content type.
        $this->configurePage->go();
        $this->configurePage->configureForm->typeBasicPage->check();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');

        // Publish the page.
        $this->nodeEditPage->go($page_nid);
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();
        $this->adminViewPage->contextualToolbar->buttonPublish->click();

        // When the node is panelized, the time stamp is shown in a pane on the rightside.
        // Verify that the timestamp div is present.
        $this->frontEndViewPage->go($page_nid);
        $xpath = '//div[contains(@class,"region-content")]/div[contains(@class, "field-name-field-paddle-timestamp")]';
        $this->waitUntilElementIsPresent($xpath);

        // Choose a defined timestamp.
        $this->nodeEditPage->go($page_nid);
        $this->nodeEditPage->generateTimestamp->uncheck();
        //$this->waitUntilElementIsDisplayed($this->nodeEditPage->timestampDate);
        $this->nodeEditPage->timestampDate->fill('04/05/1985');
        $this->nodeEditPage->timestampHour->fill('11:45');
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();
        $this->adminViewPage->contextualToolbar->buttonPublish->click();

        $this->frontEndViewPage->go($page_nid);
        $this->assertTextPresent('Published on:');
        $this->assertTextPresent('04-05-1985');
    }

    /**
     * Tests if the Timestamp is correctly set after having a node scheduled for publication.
     */
    public function testScheduleForPublication()
    {
        // Go to the configuration page and enable the checkbox for this content type.
        $this->configurePage->go();
        $this->configurePage->configureForm->typeBasicPage->check();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');

        // Schedule the page for publication as Editor.
        $this->userSessionService->switchUser('Editor');
        // Create a landing page.
        $nid = $this->contentCreationService->createBasicPage();
        $this->nodeEditPage->go($nid);
        $date = format_date(strtotime('+1 days'), 'custom', 'd/m/Y');
        $this->nodeEditPage->publishOnDate->value($date);
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        // Accept the page as Chief Editor.
        $this->userSessionService->switchUser('ChiefEditor');
        $this->adminViewPage->go($nid);
        $this->adminViewPage->contextualToolbar->buttonSchedule->click();
        $this->adminViewPage->checkArrival();
        $status = $this->adminViewPage->nodeSummary->getMetadata('workflow', 'status');
        $this->assertEquals('Scheduled', $status['value']);

        // We cannot set the publishing on the future, as the scheduler cron
        // will use anyway the REQUEST_TIME to fetch the nodes.
        db_update('scheduler')
            ->fields(array(
                'publish_on' => REQUEST_TIME - 86400,
            ))
            ->condition('nid', $nid)
            ->execute();

        scheduler_cron();
        $this->adminViewPage->go($nid);
        $status = $this->adminViewPage->nodeSummary->getMetadata('workflow', 'status');
        $this->assertEquals('Online', $status['value']);
    }
}
