<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Calendar\PaneTest.
 */

namespace Kanooh\Paddle\App\Calendar;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\Calendar;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Pane\Calendar\DayView;
use Kanooh\Paddle\Pages\Element\Pane\Calendar\MonthListImageView;
use Kanooh\Paddle\Pages\Element\Pane\Calendar\MonthListView;
use Kanooh\Paddle\Pages\Element\Pane\Calendar\WeekListView;
use Kanooh\Paddle\Pages\Element\Pane\Calendar\ListViewEvent;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\Pane\Calendar\Calendar as CalendarPane;
use Kanooh\Paddle\Pages\Element\Pane\Calendar\MonthCalendarView;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CalendarPanelsContentType;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CalendarPanelsContentTypeForm;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage as NodeEditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Utilities\AjaxService;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\HttpRequest\HttpRequest;
use Kanooh\Paddle\Utilities\TaxonomyService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the Calendar pane.
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneTest extends WebDriverTestCase
{
    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * Admin node view page.
     *
     * @var AdminViewPage
     */
    protected $adminViewPage;

    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * @var CleanUpService
     */
    protected $cleanUpService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * Frontend node view page.
     *
     * @var ViewPage
     */
    protected $frontendViewPage;

    /**
     * Basic page layout page.
     *
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * The node edit page.
     *
     * @var NodeEditPage
     */
    protected $nodeEditPage;

    /**
     * A list of nodes created for this test.
     *
     * @var array
     */
    protected $nodes = array();

    /**
     * @var TaxonomyService
     */
    protected $taxonomyService;

    /**
     * The taxonomy terms created by this test.
     *
     * @var array
     */
    protected $taxonomyTerms = array();

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
        $this->adminViewPage = new AdminViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->assetCreationService = new AssetCreationService($this);
        $this->userSessionService = new UserSessionService($this);
        $this->cleanUpService = new CleanUpService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->frontendViewPage = new ViewPage($this);
        $this->layoutPage = new LayoutPage($this);
        $this->nodeEditPage = new NodeEditPage($this);
        $this->taxonomyService = new TaxonomyService();

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Calendar);
    }

    /**
     * Tests the basic configuration and functionality of the Calendar pane.
     *
     * @group panes
     * @group calendar
     */
    public function testPaneConfiguration()
    {
        // Delete all Calendar Item nodes so they don't interfere with the test.
        $this->cleanUpService->deleteEntities('node', 'calendar_item');

        // Create 4 terms in the "Paddle Tags" vocabulary.
        $terms = array();
        for ($i = 0; $i < 4; $i++) {
            $title = $this->alphanumericTestDataProvider->getValidValue();
            $tid = $this->taxonomyService->createTerm(
                TaxonomyService::TAGS_VOCABULARY_ID,
                $title
            );
            $terms[] = array('tid' => $tid, 'title' => $title);
        }

        // Create two calendar item nodes.
        $nodes = array();
        $nodes[] = $this->contentCreationService->createCalendarItem();
        $nodes[] = $this->contentCreationService->createCalendarItem();

        // Create a node to use for the panes.
        $nodes[] = $this->contentCreationService->createBasicPage();

        // Add a calendar pane to the page layout of the basic page and check if
        // a text is shown saying there are no tags yet.
        $this->layoutPage->go($nodes[2]);
        $region = $this->layoutPage->display->getRandomRegion();

        $content_type = new CalendarPanelsContentType($this);
        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');

        $modal = new AddPaneModal($this);
        $modal->selectContentType($content_type);

        // We should see the first and second terms only and no checkboxes
        // should be checked.
        $this->assertDefaultFormConfiguration($content_type->getForm());
        $modal->close();
        $modal->waitUntilClosed();
        $this->layoutPage->contextualToolbar->buttonBack->click();
        $this->acceptAlert();
        $this->adminViewPage->checkArrival();

        // Add the first and second tags to the calendar item nodes and the
        // third to the basic page. Add the forth tag to the second calendar
        // item to have a calendar item with 2 tags.
        $nodes[] = $nodes[1];
        foreach ($nodes as $index => $nid) {
            $this->nodeEditPage->go($nid);
            $this->nodeEditPage->tags->clear();
            $term = $terms[$index]['title'];
            $this->nodeEditPage->tags->value($term);
            $this->nodeEditPage->tagsAddButton->click();
            $xpath = '//span[contains(@class, "at-term-text") and text() = "' . ucfirst($term) . '"]';
            $this->waitUntilElementIsPresent($xpath);

            // Save the page.
            $this->nodeEditPage->contextualToolbar->buttonSave->click();
            $this->adminViewPage->checkArrival();
        }

        // Open the Calendar item pane modal.
        $this->layoutPage->go($nodes[2]);
        $region = $this->layoutPage->display->getRandomRegion();
        $panes_before = $region->getPanes();

        $content_type = new CalendarPanelsContentType($this);
        $region->buttonAddPane->click();
        $this->waitUntilTextIsPresent('Add new pane');

        $modal = new AddPaneModal($this);
        $modal->selectContentType($content_type);

        // We should see the first and second terms only and no checkboxes
        // should be checked.
        $this->assertDefaultFormConfiguration($content_type->getForm(), $terms);

        $modal->submit();
        $modal->waitUntilClosed();

        $region->refreshPaneList();
        $panes_after = $region->getPanes();

        $pane = current(array_diff_key($panes_after, $panes_before));
        $uuid = $pane->getUuid();

        $pane->toolbar->buttonEdit->click();
        $pane->editPaneModal->waitUntilOpened();

        // Check that the values were correctly saved and edit the pane.
        $this->assertDefaultFormConfiguration($content_type->getForm(), $terms);

        // Select the first tag and change the view mode.
        $content_type->getForm()->calendarTags->getByValue($terms[0]['tid'])->check();
        $content_type->getForm()->monthListViewMode->select();

        $pane->editPaneModal->submit();
        $pane->editPaneModal->waitUntilClosed();

        // Reload pane to prevent stale states.
        $pane = new CalendarPane($this, $uuid);

        // Check the configuration again.
        $pane->toolbar->buttonEdit->click();
        $pane->editPaneModal->waitUntilOpened();

        $this->assertTrue($content_type->getForm()->calendarTags->getByValue($terms[0]['tid'])->isChecked());
        $this->assertTrue($content_type->getForm()->monthListViewMode->isSelected());

        // Change it one last time.
        $content_type->getForm()->calendarTags->getByValue($terms[0]['tid'])->check();
        $content_type->getForm()->calendarTags->getByValue($terms[1]['tid'])->check();
        $content_type->getForm()->weekListViewMode->select();

        $pane->editPaneModal->submit();
        $pane->editPaneModal->waitUntilClosed();

        // Reload pane to prevent stale states.
        $pane = new CalendarPane($this, $uuid);

        // Check it one last time.
        $pane->toolbar->buttonEdit->click();
        $pane->editPaneModal->waitUntilOpened();

        $content_type = new CalendarPanelsContentType($this);
        $this->assertTrue($content_type->getForm()->calendarTags->getByValue($terms[0]['tid'])->isChecked());
        $this->assertTrue($content_type->getForm()->calendarTags->getByValue($terms[1]['tid'])->isChecked());
        $this->assertTrue($content_type->getForm()->weekListViewMode->isSelected());

        $pane->editPaneModal->close();
        $pane->editPaneModal->waitUntilClosed();
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();
    }

    /**
     * Tests the basic configuration and functionality of the Calendar pane.
     *
     * @group panes
     * @group calendar
     */
    public function testMonthListPane()
    {
        // Delete all Calendar Item nodes so they don't interfere with the test.
        $this->cleanUpService->deleteEntities('node', 'calendar_item');

        $day_format = variable_get('date_format_full_month_no_time', 'd F Y');
        $month_format = variable_get('date_views_month_format_with_year', 'F Y');

        // Create a few calendar items, add tags to them and set different
        // durations for the nodes to test multi-day events. Given how the setUp
        // method creates the events we play with the end date to have all sorts
        // of spans:
        // - event that starts in the previous month and spans into the current;
        // - event that starts the current month and lasts half-day;
        // - event that starts in the next month and lasts 3 day;
        // - event that starts in the previous month and spans into the next month;
        // - event that starts the current month and lasts 6 days;
        // - event that has no duration;
        // - event that lasts 1 minute;
        // The rest are one-day events.
        $one_day = 60 * 60 * 24;
        $num_days_month = array(
            'current' => (int) date('t'),
            'next' => (int) date('t', strtotime('first day of next month')),
        );
        $event_durations = array(
            $one_day * 32,
            $one_day / 2,
            $one_day * 3,
            $one_day * ($num_days_month['current'] + $num_days_month['next']),
            $one_day * 6,
            0,
            60,
            $one_day,
            $one_day,
        );

        $initial_nodes = $this->setUpCalendarItems($event_durations);

        // Group the nodes per time and the expected nodes per day.
        $created_nodes = array();
        $terms = array();
        foreach ($initial_nodes as $node) {
            // Get the term IDs.
            if (!empty($node['tid'])) {
                $terms[] = $node['tid'];
            }

            $start = new \DateObject($node['start_date']);
            $start->setTime(0, 0, 0);
            $end = new \DateObject($node['end_date']);
            $end->setTime(0, 0, 0);
            $days = $end->diff($start)->days;

            // The time should be shown only on events that start and end on
            // the same day, but only if they last some time.
            $node['has_time'] = false;
            if (!$days && $node['end_date'] > $node['start_date']) {
                $node['has_time'] = true;
            }

            $start->limitGranularity(array('year', 'month', 'day'));

            for ($i = 0; $i <= $days; $i++) {
                $period = $start->format($month_format);
                $day = $start->format($day_format);

                $created_nodes[$period][$day][$node['title']] = $node;

                $start->modify('+1 day');
            }
        }

        // Get all the nodes that have tags. Intentionally remove the nodes
        // tagged with the first tag for later testing purposes.
        $tagged_nodes = array();
        // Prepare also a flat list of tagged nodes for the iCal feed assertion.
        $flat_tagged_nodes = array();
        foreach ($created_nodes as $period => $dates) {
            foreach ($dates as $day => $nodes) {
                foreach ($nodes as $nid => $node) {
                    if (!empty($node['tid']) && $node['tid'] != $terms[0]) {
                        $tagged_nodes[$period][$day][$node['title']] = $node;
                        $flat_tagged_nodes[$node['nid']] = $node;
                    }
                }
            }
        }

        // Add a pane to a basic page.
        $basic_page = $this->contentCreationService->createBasicPage();

        $this->nodes[] = $basic_page;

        // First don't select any terms so it will display all calendar items.
        $uuid = $this->addCalendarPaneToNode($basic_page, 'monthList');

        // Go to the front-end and verify that everything is well displayed.
        $this->frontendViewPage->go($basic_page);
        $pane = new CalendarPane($this, $uuid);

        $this->assertFrontEndCalendarPaneRendering($pane, $created_nodes);
        $this->assertCalendarIcalFeed($uuid, $initial_nodes, array());

        // Now add a pane with some terms checked so we check the tags filtering.
        // First filter the created nodes to exclude the ones without tags.
        unset($terms[0]);
        $uuid = $this->addCalendarPaneToNode($basic_page, 'monthList', $terms);
        $this->frontendViewPage->go($basic_page);
        $pane = new CalendarPane($this, $uuid);

        $this->assertFrontEndCalendarPaneRendering($pane, $tagged_nodes);
        $this->assertCalendarIcalFeed($uuid, $flat_tagged_nodes, $terms);
    }

    /**
     * Tests the month calendar view mode for the calendar pane.
     */
    public function testMonthCalendarPane()
    {
        // Delete all Calendar Item nodes so they don't interfere with the test.
        $this->cleanUpService->deleteEntities('node', 'calendar_item');

        // Create test nodes, with a duration of one hour.
        $nodes = $this->setUpCalendarItems(60*60);

        // Create a basic page and add a calendar pane.
        $basic_page_nid = $this->contentCreationService->createBasicPage();
        $this->nodes[] = $basic_page_nid;

        $pane_uuid = $this->addCalendarPaneToNode($basic_page_nid, 'monthCalendar');

        // Go to the frontend view.
        $this->frontendViewPage->go($basic_page_nid);

        // Group nodes by date.
        $expected_nodes = $this->groupNodesByDate($nodes);
        $this->assertEventsInMonthCalendarView($pane_uuid, $expected_nodes);

        // Verify that the iCal feed link is in place.
        $this->assertCalendarIcalFeed($pane_uuid, $nodes, array());

        $limited_nodes = array();
        $tids = array();
        for ($i = 0; $i < 6; $i++) {
            // Take one node and use its term id to filter the items in the pane.
            $node = array_shift($nodes);
            $term_id = $node['tid'];

            // Go to the node layout page.
            $this->layoutPage->go($basic_page_nid);
            // Open the calendar pane edit modal.
            $pane = new CalendarPane($this, $pane_uuid);
            $pane->toolbar->buttonEdit->click();
            $pane->editPaneModal->waitUntilOpened();
            // Check the wanted tag to filter items with.
            $content_type = new CalendarPanelsContentType($this);
            $content_type->getForm()->calendarTags->getByValue($term_id)->check();
            // Save the pane and the page.
            $pane->editPaneModal->submit();
            $pane->editPaneModal->waitUntilClosed();
            $this->layoutPage->contextualToolbar->buttonSave->click();
            $this->adminViewPage->checkArrival();
            // Go to the frontend view again.
            $this->frontendViewPage->go($basic_page_nid);

            // Group nodes by date.
            $limited_nodes[] = $node;
            $expected_nodes = $this->groupNodesByDate($limited_nodes);
            $this->assertEventsInMonthCalendarView($pane_uuid, $expected_nodes);

            // Verify the iCal feed link.
            $tids[] = $term_id;
            $this->assertCalendarIcalFeed($pane_uuid, $limited_nodes, $tids);
        }
    }

    /**
     * Tests events that span between multiple days in the month views.
     */
    public function testMonthViewModesEventSpanning()
    {
        // Delete all Calendar Item nodes so they don't interfere with the test.
        $this->cleanUpService->deleteEntities('node', 'calendar_item');

        // Amounts of second in one day.
        $one_day = 60 * 60 * 24;

        // Create one calendar item that spans 2 days in the current month.
        // Span less than 24h to ensure day difference is calculated based on
        // days, not on hours.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $start_date = strtotime('second monday of 10:00');
        $end_date = strtotime('second monday of +1 days 09:00');
        $item_nid = $this->contentCreationService->createCalendarItem($title, $start_date, $end_date);

        // Publish the node.
        $this->adminViewPage->go($item_nid);
        $this->adminViewPage->contextualToolbar->buttonPublish->click();
        $this->adminViewPage->checkArrival();

        // Mark the node for deletion.
        $this->nodes[] = $item_nid;

        foreach (array('monthCalendar', 'monthList', 'monthListImage') as $view_mode) {
            // Create a basic page and add a calendar pane.
            $basic_page_nid = $this->contentCreationService->createBasicPage();
            $this->nodes[] = $basic_page_nid;

            $pane_uuid = $this->addCalendarPaneToNode($basic_page_nid, $view_mode);

            // Go to the frontend view.
            $this->frontendViewPage->go($basic_page_nid);

            // Get the calendar pane and its view.
            $pane = new CalendarPane($this, $pane_uuid);

            // Verify that the event is shown in those and only those 2 days.
            if ($view_mode == 'monthCalendar') {
                /* @var MonthCalendarView $content */
                $content = $pane->getPaneContent();
                for ($i = 0; $i < 2; $i++) {
                    $date_to_check = $start_date + ($one_day * $i);
                    $this->assertNotFalse($content->getCellByDay($date_to_check));
                }
                $this->assertCount(2, $content->getDaysWithEvents());
            } elseif ($view_mode == 'monthList') {
                /* @var MonthListView $content */
                $content = $pane->getPaneContent();
                $this->assertCount(2, $content->days);
            }
        }
    }

    /**
     * Tests the day view when clicking a day with events in the
     * month calendar view.
     */
    public function testDayView()
    {
        // Delete all Calendar Item nodes so they don't interfere with the test.
        $this->cleanUpService->deleteEntities('node', 'calendar_item');

        // Create a set of calendar items to cover cases.
        $single_day_title = $this->alphanumericTestDataProvider->getValidValue();
        $single_day_start = 'first monday of 10AM +1 days';
        $single_day_end = 'first monday of 1PM +1 days';
        $cases = array(
            array(
                'start_date' => 'first monday of 10AM',
                'end_date' => 'first monday of 1PM +2 days',
            ),
            array(
                'start_date' => 'first monday of 10AM +1 days',
                'end_date' => 'first monday of 1PM +3 days',
            ),
            array(
                'start_date' => 'first monday of 10AM +1 days',
                'end_date' => 'first monday of 10AM +1 days',
            ),
            array(
                'title' => $single_day_title,
                'start_date' => $single_day_start,
                'end_date' => $single_day_end,
            ),
            array(
                'start_date' => 'first monday of 10AM',
                'end_date' => 'first monday of 1PM +1 days',
            ),
            // Create a node on the previous month to see that ajax will not
            // break.
            array(
                'start_date' => 'first monday of previous month 10AM',
                'end_date' => 'first monday of previous month 1PM +1 days',
            ),
        );

        $nodes = array();
        foreach ($cases as $case) {
            $info = array(
                'title' => !empty($case['title'])
                    ? $case['title']
                    : $this->alphanumericTestDataProvider->getValidValue(),
                'start_date' => strtotime($case['start_date']),
                'end_date' => strtotime($case['end_date']),
            );

            $nid = $this->contentCreationService->createCalendarItem(
                $info['title'],
                $info['start_date'],
                $info['end_date']
            );
            $info['nid'] = $nid;

            // Mark the node for deletion.
            $this->nodes[] = $nid;

            // Publish the node.
            $this->adminViewPage->go($nid);
            $this->adminViewPage->contextualToolbar->buttonPublish->click();
            $this->adminViewPage->checkArrival();

            $nodes[] = $info;
        }

        // Create a basic page and add a calendar pane.
        $basic_page_nid = $this->contentCreationService->createBasicPage();
        $this->nodes[] = $basic_page_nid;

        $pane_uuid = $this->addCalendarPaneToNode($basic_page_nid, 'monthCalendar');

        // Go to the frontend view.
        $this->frontendViewPage->go($basic_page_nid);

        // This event is in the previous month.
        $previous_month_item = array_pop($nodes);

        // Get the calendar pane and its view.
        $pane = new CalendarPane($this, $pane_uuid);
        // Go to the previous month.
        $pane->previousPeriod();
        /* @var MonthCalendarView $content */
        $content = $pane->getPaneContent();

        // Get the cell with the event.
        $start = new \DateObject($previous_month_item['start_date']);
        $timestamp = $start->getTimestamp();
        $cell = $content->getCellByDay($timestamp);
        $this->assertTrue($cell->hasEvents());
        // Click the cell link.
        $cell->dayLink->click();
        $this->waitUntilElementIsPresent('//div[contains(@class, "view-display-id-day_view")]');

        // Verify the event in the day view.
        /* @var DayView $day_view */
        $day_view = $content->getDayView();
        $events = $day_view->events;

        foreach ($events as $event) {
            $this->assertEquals($event->title, $previous_month_item['title']);
        }

        // Go back to the current month.
        $pane->nextPeriod();

        // Group the nodes per time and the expected nodes per day..
        $grouped_nodes = array();
        foreach ($nodes as $info) {
            $start = new \DateObject($info['start_date']);
            $end = new \DateObject($info['end_date']);
            $days = $end->diff($start)->days;

            $start->limitGranularity(array('year', 'month', 'day'));
            $grouped_nodes[$start->getTimestamp()][] = $info;

            for ($i = 0; $i < $days; $i++) {
                $start->modify('+1 day');
                $grouped_nodes[$start->getTimestamp()][] = $info;
            }
        }

        $ajax_service = new AjaxService($this);

        foreach ($grouped_nodes as $timestamp => $expected_nodes) {
            // Get the calendar pane and its view.
            $pane = new CalendarPane($this, $pane_uuid);
            /* @var MonthCalendarView $content */
            $content = $pane->getPaneContent();

            $cell = $content->getCellByDay($timestamp);
            $this->assertTrue($cell->hasEvents());

            // Click the cell link.
            $ajax_service->markAsWaitingForAjaxCallback($cell->dayLink);
            $cell->dayLink->click();
            $ajax_service->waitForAjaxCallback($cell->dayLink);

            // Reload the pane content otherwise it becomes stale.
            /* @var MonthCalendarView $content */
            $content = $pane->getPaneContent();

            $events = $content->getDayView()->events;
            $this->assertSameSize($expected_nodes, $events);

            foreach ($events as $event) {
                /* @var ListViewEvent $event */
                $event_title = $event->title;

                // Loop the nodes list to find our event.
                foreach ($expected_nodes as $index => $node) {
                    if ($event_title == $node['title']) {
                        // If the event title is the same as the single day
                        // title, then check if the time is there.
                        if ($event_title == $single_day_title) {
                            $this->assertEquals($event->startTime, date('H:i', strtotime($single_day_start)));
                            $this->assertEquals($event->endTime, date('H:i', strtotime($single_day_end)));
                        } else {
                            $time = null;
                            try {
                                $time = $event->time;
                            } catch (\PHPUnit_Extensions_Selenium2TestCase_Exception $e) {
                                // The element is not there, cool.
                            }

                            $this->assertNull($time);
                        }
                    }
                }
            }
        }
    }

    /**
     * Tests the week list view of a pane.
     */
    public function testWeekListView()
    {
        // Delete all Calendar Item nodes so they don't interfere with the test.
        $this->cleanUpService->deleteEntities('node', 'calendar_item');

        // Create one calendar item that spans 1 day.
        $single_title = $this->alphanumericTestDataProvider->getValidValue();
        $single_start_date = strtotime('this friday 09:00');
        $single_end_date = strtotime('this friday 16:00');
        $item_nid = $this->contentCreationService->createCalendarItem($single_title, $single_start_date, $single_end_date);

        // Publish the node.
        $this->adminViewPage->go($item_nid);
        $this->adminViewPage->contextualToolbar->buttonPublish->click();
        $this->adminViewPage->checkArrival();

        // Mark the node for deletion.
        $this->nodes[] = $item_nid;

        // Create one calendar item that spans 2 days in the current week. Span
        // less than 24h to ensure day difference is calculated on days, not on
        // hours.
        $multi_this_title = $this->alphanumericTestDataProvider->getValidValue();
        $start_date = strtotime('tuesday this week 10:00');
        $end_date = strtotime('wednesday this week 09:00');
        $item_nid = $this->contentCreationService->createCalendarItem($multi_this_title, $start_date, $end_date);

        // Publish the node.
        $this->adminViewPage->go($item_nid);
        $this->adminViewPage->contextualToolbar->buttonPublish->click();
        $this->adminViewPage->checkArrival();

        // Mark the node for deletion.
        $this->nodes[] = $item_nid;

        // Create one calendar item that spans multiple weeks.
        $multi_week_title = $this->alphanumericTestDataProvider->getValidValue();
        $start_date = strtotime('wednesday last week');
        $end_date = strtotime('Tue next week');
        $item_nid = $this->contentCreationService->createCalendarItem($multi_week_title, $start_date, $end_date);

        // Publish the node.
        $this->adminViewPage->go($item_nid);
        $this->adminViewPage->contextualToolbar->buttonPublish->click();
        $this->adminViewPage->checkArrival();

        // Mark the node for deletion.
        $this->nodes[] = $item_nid;

        // Create a basic page and add a calendar pane.
        $basic_page_nid = $this->contentCreationService->createBasicPage();
        $this->nodes[] = $basic_page_nid;

        $pane_uuid = $this->addCalendarPaneToNode($basic_page_nid, 'weekList');

        // Go to the frontend view.
        $this->frontendViewPage->go($basic_page_nid);

        // Get the calendar pane and its view.
        $pane = new CalendarPane($this, $pane_uuid);
        /* @var WeekListView $content */
        $content = $pane->getPaneContent();

        // Make sure we have only and exactly 7 days displayed.
        $this->assertCount(7, $content->getDaysForWeek());

        // Assert week days are correctly ordered.
        $days_of_the_week = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
        $days_of_the_week = array_map('strtoupper', $days_of_the_week);
        $this->assertEquals($days_of_the_week, array_keys($content->getDaysForWeek()));

        // Verify the correct number of events are displayed, on each day.
        $expected_week_counts = array(
            'Mon' => 1,
            'Tue' => 2,
            'Wed' => 2,
            'Thu' => 1,
            'Fri' => 2,
            'Sat' => 1,
            'Sun' => 1,
        );
        foreach ($expected_week_counts as $day_title => $count) {
            $this->assertEquals($count, $content->getNumberOfEventsForDay($day_title), "$day_title wast nie");
        }

        // Verify that the single day event is shown with the start and end
        // time.
        /* @var ListViewEvent $event */
        $event = $content->getEventByDayAndTitle('Fri', $single_title);
        $this->assertNotEmpty($event);
        $this->assertEquals($event->endTime, '16:00');
        $this->assertEquals($event->startTime, '09:00');

        // Verify that the multi day event this week is shown without a start
        // and end time.
        $event = $content->getEventByDayAndTitle('Tue', $multi_this_title);
        $this->assertNotEmpty($event);
        $this->assertEmpty($event->time);

        $event = $content->getEventByDayAndTitle('Wed', $multi_this_title);
        $this->assertNotEmpty($event);
        $this->assertEmpty($event->time);

        // Verify that the multi week event this week is shown without a start
        // and end time.
        $event = $content->getEventByDayAndTitle('Wed', $multi_week_title);
        $this->assertNotEmpty($event);
        $this->assertEmpty($event->time);

        $event = $content->getEventByDayAndTitle('Sun', $multi_week_title);
        $this->assertNotEmpty($event);
        $this->assertEmpty($event->time);

        // We need a list of node titles for the iCal assertion.
        $node_titles = array(
            array('title' => $single_title),
            array('title' => $multi_this_title),
            array('title' => $multi_week_title),
        );

        // Date module doesn't use ISO week 'W' like the date() function does.
        $now = date_now();
        $week = date_week(date_format($now, 'Y-m-d'));
        $date_string = date_format($now, 'o') . '-W' . date_pad($week);

        // Assert that the iCal feed is showing.
        $this->assertCalendarIcalFeed($pane_uuid, $node_titles, array(), $date_string);

        // Go to the next week and verify the correct values are shown.
        $pane->nextPeriod();
        $content = $pane->getPaneContent();

        // Make sure we have only and exactly 7 days displayed.
        $this->assertCount(7, $content->getDaysForWeek());

        // Assert week days are correctly ordered.
        $this->assertEquals($days_of_the_week, array_keys($content->getDaysForWeek()));

        // Make sure all the days of the week are displayed even if they have no events.
        $expected_week_counts = array(
            'Mon' => 1,
            'Tue' => 1,
            'Wed' => 0,
            'Thu' => 0,
            'Fri' => 0,
            'Sat' => 0,
            'Sun' => 0,
        );
        foreach ($expected_week_counts as $day_title => $count) {
            $this->assertEquals($count, $content->getNumberOfEventsForDay($day_title));
        }

        $event = $content->getEventByDayAndTitle('Tue', $multi_week_title);
        $this->assertNotEmpty($event);
        $this->assertEmpty($event->time);

        $event = $content->getEventByDayAndTitle('Wed', $multi_week_title);
        $this->assertFalse($event);

        // Assert that the iCal feed is showing.
        $this->assertCalendarIcalFeed($pane_uuid, $node_titles, array(), $date_string);

        // Go 2 weeks back and verify the correct values are shown. The previous
        // period function does not work because the element is no longer
        // attached to the DOM.
        $pane->previousPeriod();
        $pane->previousPeriod();
        $content = $pane->getPaneContent();

        // Make sure we have only and exactly 7 days displayed.
        $this->assertCount(7, $content->getDaysForWeek());

        // Assert week days are correctly ordered.
        $this->assertEquals($days_of_the_week, array_keys($content->getDaysForWeek()));

        // Make sure all the days of the week are displayed even if they have no events.
        $expected_week_counts = array(
            'Mon' => 0,
            'Tue' => 0,
            'Wed' => 1,
            'Thu' => 1,
            'Fri' => 1,
            'Sat' => 1,
            'Sun' => 1,
        );
        foreach ($expected_week_counts as $day_title => $count) {
            $this->assertEquals($count, $content->getNumberOfEventsForDay($day_title));
        }

        $event = $content->getEventByDayAndTitle('Wed', $multi_week_title);
        $this->assertNotEmpty($event);
        $this->assertEmpty($event->time);

        $event = $content->getEventByDayAndTitle('Tue', $multi_week_title);
        $this->assertFalse($event);

        // Assert that the iCal feed is showing.
        $this->assertCalendarIcalFeed($pane_uuid, $node_titles, array(), $date_string);
    }

    /**
     * Tests the month list image view pane.
     *
     * @group panes
     * @group calendar
     */
    public function testMonthListImagePane()
    {
        // Delete all Calendar Item nodes so they don't interfere with the test.
        $this->cleanUpService->deleteEntities('node', 'calendar_item');
        $atom = $this->assetCreationService->createImage();

        // We just test for 1 item since all settings are the same / copied from the regular month list pane.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $time = time();
        $item = $this->contentCreationService->createCalendarItem($title, $time);
        $this->nodeEditPage->go($item);
        $this->nodeEditPage->featuredImage->selectAtom($atom['id']);
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();
        $this->adminViewPage->contextualToolbar->buttonPublish->click();
        $this->adminViewPage->checkArrival();

        // Add a pane to a basic page.
        $basic_page = $this->contentCreationService->createBasicPage();
        $uuid = $this->addCalendarPaneToNode($basic_page, 'monthListImage');

        // Go to the front-end and verify that everything is well displayed.
        $this->frontendViewPage->go($basic_page);
        $pane = new CalendarPane($this, $uuid);

        /** @var MonthListImageView $pane_content */
        $pane_content = $pane->getPaneContent();

        foreach ($pane_content->days as $day) {
            foreach ($day->events as $event) {
                // Verify that the title, day and image are shown.
                $this->assertEquals($title, $event->title);
                $this->assertEquals(date('d F Y', $time), $event->singleDate);
                $this->assertNotEmpty($event->featuredImage);
            }
        }

        // Assert that the iCal feed is showing.
        $this->assertCalendarIcalFeed($uuid, array($title), array());
    }

    /**
     * Asserts that only the wanted events are shown in a month calendar.
     *
     * @param string $pane_uuid
     *   The uuid of the calendar pane we want to test.
     * @param array $nodes
     *   The nodes we expect to find in the pane.
     */
    protected function assertEventsInMonthCalendarView($pane_uuid, $nodes)
    {
        $pane = new CalendarPane($this, $pane_uuid);

        // Move to the previous month so we can check easily the three months.
        $pane->previousPeriod();

        for ($i = 0; $i < 3; $i++) {
            // Load back the content that changed after the ajax callback.
            /* @var MonthCalendarView $content */
            $content = $pane->getPaneContent();

            // Start with an empty count of events.
            $count = 0;

            // Verify that the correct events are in there.
            if (isset($nodes[$pane->periodTitle])) {
                foreach ($nodes[$pane->periodTitle] as $node) {
                    $this->assertNotFalse($content->getCellByDay($node['start_date']));
                }

                // Set the expected nodes count.
                $count = count($nodes[$pane->periodTitle]);

                // Remove the period from the list, so we know that we tested
                // this group.
                unset($nodes[$pane->periodTitle]);
            }

            // Verify that the event count matches the expected, so we can
            // catch any other event showing.
            $this->assertCount($count, $content->getDaysWithEvents());

            // Go to the next month as long as we did not reach the end of the
            // list of months yet.
            if ($i < 2) {
              $pane->nextPeriod();
            }
        }

        // All node periods should have been tested by now.
        $this->assertCount(0, $nodes);
    }

    /**
     * Checks the default Calendar pane configuration.
     *
     * @param CalendarPanelsContentTypeForm $form
     *   The pane configuration form.
     * @param array $terms
     *   Array of terms to look for.
     */
    public function assertDefaultFormConfiguration($form, $terms = array())
    {
        if (!empty($terms)) {
            $tags = $form->calendarTags;
            foreach ($terms as $index => $term) {
                if ($index != 2) {
                    $this->assertTrue($tags->getByValue($term['tid']) instanceof Checkbox);
                    $this->assertFalse($tags->getByValue($term['tid'])->isChecked());
                } else {
                    $this->assertFalse($tags->getByValue($term['tid']));
                }
            }
        } else {
            $this->assertTextPresent('You can use tags to categorize calendars. Currently you have no tagged calendar items.');
        }

        // Check that the "Month calendar view" is selected by default.
        $this->assertTrue($form->monthCalendarViewMode->isSelected());
    }

    /**
     * Create a few tags and calendar item nodes.
     *
     * @param mixed $duration
     *   The periods to add to the start date, to use as end date for each node.
     *   Defaults to 1 day for each node created. If a scalar value is passed
     *   the same value will be used for all nodes.
     *
     * @return array
     *   Array containing the node data including title, start & end date and tid.
     */
    protected function setUpCalendarItems($duration = null)
    {
        $nodes = array();

        // Create 6 terms in the "Paddle Tags" vocabulary.
        $terms = array();
        for ($i = 0; $i < 6; $i++) {
            $title = $this->alphanumericTestDataProvider->getValidValue();
            $tid = $this->taxonomyService->createTerm(
                TaxonomyService::TAGS_VOCABULARY_ID,
                $title
            );
            $terms[] = array('tid' => $tid, 'title' => $title);

            // Mark the term to be deleted at the end of the test.
            $this->taxonomyTerms[] = $tid;
        }

        // If a duration for a calendar item is not set, set to the default.
        $duration = !$duration ? 86400 : $duration;
        if (!is_array($duration)) {
            $duration = array_fill(count($duration), 6 - count($duration), $duration);
        }

        // Create calendar items to test with. They should have different dates
        // so it appear in different months.
        $one_day = 60 * 60 * 24;

        for ($i = 0; $i < 8; $i++) {
            $title = $this->alphanumericTestDataProvider->getValidValue();
            switch ($i % 3) {
                case 0:
                    // Put it in the previous month.
                    $start_date = $one_day * $i + strtotime('first day of previous month', time());
                    break;
                case 1:
                    // Put it in the current month.
                    $start_date = $one_day * $i + strtotime('first day of ', time());
                    break;
                case 2:
                default:
                    // Put it in the next month.
                    $start_date = $one_day * $i + strtotime('first day of next month', time());
                    break;
            }

            $end_date = $start_date + $duration[$i];
            $nid = $this->contentCreationService->createCalendarItem($title, $start_date, $end_date);

            $nodes[$nid] = array(
                'nid' => $nid,
                'title' => $title,
                'start_date' => $start_date,
                'end_date' => $end_date,
            );

            // Add tags to the nodes. We need to check that only nodes tagged
            // with correct tags are displayed.
            if (!empty($terms[$i])) {
                $nodes[$nid]['tid'] = $terms[$i]['tid'];
                $this->nodeEditPage->go($nid);
                $this->nodeEditPage->tags->clear();
                $term = $terms[$i]['title'];
                $this->nodeEditPage->tags->value($term);
                $this->nodeEditPage->tagsAddButton->click();
                $xpath = '//span[contains(@class, "at-term-text") and text() = "' . ucfirst($term) . '"]';
                $this->waitUntilElementIsPresent($xpath);

                // Save the page.
                $this->nodeEditPage->contextualToolbar->buttonSave->click();
                $this->adminViewPage->checkArrival();
            } else {
                $this->adminViewPage->go($nid);
            }

            // Now publish.
            $this->adminViewPage->contextualToolbar->buttonPublish->click();
            $this->adminViewPage->checkArrival();
        }

        // Mark the nodes to be deleted at the end of the test.
        $this->nodes += array_keys($nodes);

        return $nodes;
    }

    /**
     * Adds a Calendar pane to the node provided.
     *
     * @param string $nid
     *   The nid of the node to which we want the Calendar pane added.
     * @param string $view_mode
     *   The view mode of the pane. Can be one of 'monthList', 'monthCalendar'
     *   or 'weekList'.
     * @param array $terms
     *   Array containing the term IDs by which we want to filter the nodes.
     *
     * @return string
     *   The uuid of the pane created.
     */
    protected function addCalendarPaneToNode($nid, $view_mode, $terms = array())
    {
        // Add a calendar pane to the node.
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();

        $content_type = new CalendarPanelsContentType($this);
        $callable = new SerializableClosure(
            function () use ($content_type, $view_mode, $terms) {
                $content_type->getForm()->{$view_mode . 'ViewMode'}->select();

                if (!empty($terms)) {
                    foreach ($terms as $tid) {
                        $content_type->getForm()->calendarTags->getByValue($tid)->check();
                    }
                }
            }
        );
        $pane = $region->addPane($content_type, $callable);

        $pane_uuid = $pane->getUuid();

        // Save the page.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        return $pane_uuid;
    }

    /**
     * Asserts that the correct data is rendered in the pane on the front-end.
     *
     * @param CalendarPane $pane
     *   The Pane object.
     * @param array $nodes
     *   Node objects in a nested array keyed by period title, day title and
     *   event title.
     */
    protected function assertFrontEndCalendarPaneRendering(CalendarPane $pane, array $nodes)
    {
        // Move to the previous month so we can check easily the three months.
        $pane->previousPeriod();

        for ($i = 0; $i < 3; $i++) {
            // Verify that the month title is present in the expected nodes.
            $this->assertArrayHasKey($pane->periodTitle, $nodes);

            /** @var MonthListView $pane_content */
            $pane_content = $pane->getPaneContent();

            foreach ($pane_content->days as $day) {
                // Verify that we expected this day to be shown.
                $this->assertArrayHasKey($day->title, $nodes[$pane->periodTitle]);

                // Extract the nodes that we expect in this day for code
                // readability and to speed up assertions, avoiding to
                // query Selenium for all the dynamic properties.
                $day_nodes = $nodes[$pane->periodTitle][$day->title];

                // Verify that we have the expected number of events in this day.
                $this->assertSameSize($day_nodes, $day->events, $day->title);

                foreach ($day->events as $event) {
                    $this->assertArrayHasKey($event->title, $day_nodes);

                    // Assert that the event itself matches the created node.
                    $node = $day_nodes[$event->title];
                    $expected_url = url('node/' . $node['nid'], array('absolute' => true));
                    $this->assertEquals($expected_url, $event->link->attribute('href'));

                    if ($node['has_time']) {
                        $this->assertEquals($event->startTime, date('H:i', $node['start_date']));
                        $this->assertEquals($event->endTime, date('H:i', $node['end_date']));
                    } else {
                        $this->assertEmpty($event->time);
                    }
                }
            }

            // Go to the next month if we are not at the last month of the list.
            if ($i < 2) {
                $pane->nextPeriod();
            }
        }
    }

    /**
     * Groups nodes by a date format.
     *
     * @param array $nodes
     *   An array of node information.
     * @param string $format
     *   The date format to use for grouping.
     *
     * @return array
     *   The grouped node info.
     */
    protected function groupNodesByDate($nodes, $format = 'F Y')
    {
        $expected_nodes = array();
        foreach ($nodes as $node) {
            $date = format_date($node['start_date'], 'custom', $format);
            $expected_nodes[$date][] = $node;
        }

        return $expected_nodes;
    }

    /**
     * Asserts that the calendar iCal feed link is present and correct.
     *
     * @param string $pane_uuid
     *   The uuid of the calendar pane we want to test.
     * @param array $nodes
     *   The nodes we expect to find in the feed.
     * @param array $tids
     *   The term ids the pane is configured to show.
     * @param string $date_string
     *   The date as string, expected to be in the url.
     */
    protected function assertCalendarIcalFeed($pane_uuid, $nodes, $tids, $date_string = null)
    {
        $pane = new CalendarPane($this, $pane_uuid);

        // Get the feed link href.
        $url = $pane->icalFeedLink->attribute('href');

        // Split the urls in parts. Drupal own drupal_parse_url() doesn't fit
        // our needs.
        $parts = parse_url($url);

        // Assert that the protocol is webcal.
        $this->assertEquals('webcal', $parts['scheme']);

        // Prepare the parts of the links we expect to find.
        if (empty($date_string)) {
            $date_string = format_date(time(), 'custom', 'Y-m');
        }
        $tids_string = count($tids) ? implode('+', $tids) : 'all';

        $expected_path = url("paddle-calendar/$date_string/$tids_string/calendar.ics");

        // Assert that the path matches the expected.
        $this->assertEquals($expected_path, $parts['path']);

        // Make a request to the url, to verify its contents.
        $request = new HttpRequest($this);
        $request->setMethod(HttpRequest::GET);
        // Swap webcal with http.
        $url = str_replace('webcal://', 'http://', $url);
        $request->setUrl($url);
        $response = $request->send();

        // Verify that the url works, the response is not empty, that starts
        // with the vcalendar format, and contains our title as summary.
        $this->assertEquals(200, $response->status);
        $this->assertNotEmpty($response->responseText);
        $this->assertStringStartsWith('BEGIN:VCALENDAR', $response->responseText);

        // Verify that each expected node is inside the feed.
        foreach ($nodes as $node) {
            $this->assertContains("SUMMARY:{$node['title']}", $response->responseText);
        }

        // Verify that only the expected number of nodes are present.
        preg_match_all('/^SUMMARY:.+$/m', $response->responseText, $matches);
        $this->assertSameSize($nodes, $matches[0]);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Delete all nodes created by this test.
        if (!empty($this->nodes)) {
            node_delete_multiple($this->nodes);
        }

        // Delete all the taxonomy terms created by this test.
        if (!empty($this->taxonomyTerms)) {
            foreach ($this->taxonomyTerms as $tid) {
                taxonomy_term_delete($tid);
            }
        }

        parent::tearDown();
    }
}
