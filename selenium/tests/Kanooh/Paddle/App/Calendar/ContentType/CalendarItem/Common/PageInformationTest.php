<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Calendar\ContentType\CalendarItem\Common\PageInformationTest.
 */

namespace Kanooh\Paddle\App\Calendar\ContentType\CalendarItem\Common;

use Kanooh\Paddle\Apps\Calendar;
use Kanooh\Paddle\Core\ContentType\Base\PageInformationTestBase;
use Kanooh\Paddle\Utilities\AppService;

/**
 * Class PageInformationTest
 * @package Kanooh\Paddle\App\Calendar\ContentType\CalendarItem\Common
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PageInformationTest extends PageInformationTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupPage()
    {
        parent::setUpPage();

        $service = new AppService($this, $this->userSessionService);
        $service->enableApp(new Calendar);
    }

    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createCalendarItem($title);
    }

    /**
     * Tests that in the edit page we have the hint about calendar tagging.
     *
     * @see https://one-agency.atlassian.net/browse/KANWEBS-3595
     */
    public function testTagsHintOnEditPage()
    {
        // Create a test node and go to its edit page.
        $nid = $this->setupNode();
        $this->editPage->go($nid);

        // Assert that the tag hint is present.
        $this->assertTextPresent('Need more calendars? Use tags to categorize them.');
    }
}
