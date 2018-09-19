<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Calendar\CalendarTest.
 */

namespace Kanooh\Paddle\App\Calendar;

use Kanooh\Paddle\Apps\Calendar;
use Kanooh\Paddle\Pages\Node\ViewPage\CalendarItemViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\HttpRequest\HttpRequest;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on the Calendar Paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class CalendarTest extends WebDriverTestCase
{
    /**
     * Test data provider for alphanumeric data.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The front end node view page.
     *
     * @var CalendarItemViewPage
     */
    protected $frontendViewPage;

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
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->frontendViewPage = new CalendarItemViewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Calendar);
    }

    /**
     * Tests the presence of the iCal link extra field in the node view page.
     */
    public function testIcalLinkField()
    {
        // Create a calendar item.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $nid = $this->contentCreationService->createCalendarItem($title);
        $this->frontendViewPage->go($nid);

        // Get the iCal feed link href.
        $url = $this->frontendViewPage->icalLinkField->attribute('href');

        // Assert that the path matches the expected.
        $expected = url("node/$nid/paddle-calendar/event.ics", array('absolute' => true));
        $this->assertEquals($expected, $url);

        // Make a request to the url, to verify its contents.
        $request = new HttpRequest($this);
        $request->setMethod(HttpRequest::GET);
        $request->setUrl($url);
        $response = $request->send();

        // Verify that the url works, the response is not empty, that starts
        // with the vcalendar format, and contains our title as summary.
        $this->assertEquals(200, $response->status);
        $this->assertNotEmpty($response->responseText);
        $this->assertStringStartsWith('BEGIN:VCALENDAR', $response->responseText);
        $this->assertContains("SUMMARY:$title", $response->responseText);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Log out.
        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->logout();

        parent::tearDown();
    }
}
