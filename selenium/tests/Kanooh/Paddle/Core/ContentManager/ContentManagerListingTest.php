<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentManager\ContentManagerListingTest.
 */

namespace Kanooh\Paddle\Core\ContentManager;

use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPage;
use Kanooh\Paddle\Pages\Admin\DashboardPage\DashboardPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPageContentTableRow;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Element\Datepicker\Datepicker;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on the Paddle Content Manager listing pages.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ContentManagerListingTest extends WebDriverTestCase
{
    /**
     * @var AddPage
     */
    protected $addContentPage;

    /**
     * @var ViewPage
     */
    protected $adminNodeViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var DashboardPage
     */
    protected $dashboardPage;

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
        $this->editPage = new EditPage($this);
        $this->searchPage = new SearchPage($this);

        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as an editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Data provider for testNodeDepublishBulkActions.
     *
     * @return array
     *   States to test with.
     */
    public function states()
    {
        return array(array('Archived'), array('Offline'));
    }

    /**
     * Tests the content of a node's row in the content management table.
     *
     * @group workflow
     */
    public function testRowContent()
    {
        $nid = $this->contentCreationService->createBasicPage();

        $this->searchPage->go();

        /* @var SearchPageContentTableRow $node_row */
        $node_row = $this->searchPage->contentTable->getNodeRowByNid($nid);
        $node_row->links->linkTitle->click();

        $this->adminNodeViewPage->checkArrival();
        $this->assertEquals($nid, $this->adminNodeViewPage->getNodeIDFromUrl());
    }

    /**
     * Tests the exposed filters on the search page.
     *
     * @group workflow
     */
    public function testSearchPageFilters()
    {
        $this->searchPage->go();
        $this->searchPage->advancedOptions->click();
        // Verify that the minimum created date has a month and year select box.
        $this->searchPage->createdMinDatepicker->focus();
        $this->assertDatepickerHasMonthAndYearSelectBoxes(
            $this->searchPage->createdMinDatepicker,
            'Minimum created date has both a month and a year select box.'
        );

        // Verify that the maximum created date has a month and year select box.
        $this->searchPage->createdMaxDatepicker->focus();
        $this->assertDatepickerHasMonthAndYearSelectBoxes(
            $this->searchPage->createdMaxDatepicker,
            'Maximum created date has both a month and a year select box.'
        );

        // Verify that the minimum created date has a month and year select box.
        $this->searchPage->modifiedMinDatepicker->focus();
        $this->assertDatepickerHasMonthAndYearSelectBoxes(
            $this->searchPage->modifiedMinDatepicker,
            'Minimum modified date has both a month and a year select box.'
        );

        // Verify that the minimum created date has a month and year select box.
        $this->searchPage->modifiedMaxDatepicker->focus();
        $this->assertDatepickerHasMonthAndYearSelectBoxes(
            $this->searchPage->modifiedMaxDatepicker,
            'Maximum modified date has both a month and a year select box.'
        );

        // Verify that the "Has an online version" is present.
        $this->assertTrue($this->searchPage->hasOnlineVersionSelect->isDisplayed());
    }

    /**
     * Tests the bulk actions on the content manager search page.
     *
     * @group bulkActions
     */
    public function testBulkActions()
    {
        // Create a node, otherwise the select all checkbox is not visible.
        $first_nid = $this->contentCreationService->createBasicPage();
        // Go to the search page and verify that the bulk actions select box and
        // execute button are present.
        $this->searchPage->go();
        $this->assertTrue($this->searchPage->bulkActions->selectAction->isDisplayed());
        $this->assertTrue($this->searchPage->bulkActions->executeButton->displayed());
        $this->assertTrue($this->searchPage->bulkActions->selectAll->isDisplayed());

        // Create another node and change its moderation status to "To editor".
        // Do this to be able to see that the nodes with different moderation
        // status still get updated when we perform bulk action on nodes with
        // different moderation state.
        $second_nid = $this->contentCreationService->createBasicPage();
        $this->adminNodeViewPage->go($second_nid);
        $this->adminNodeViewPage->contextualToolbar->buttonToEditor->click();
        $this->waitUntilTextIsPresent('assign to any');
        $this->adminNodeViewPage->contextualToolbar->buttonToAnyEditor->click();
        $this->waitUntilTextIsPresent('To check');
        // Go back to the Content manager page and check the state.
        $this->searchPage->go();
        /* @var $node_row SearchPageContentTableRow */
        $second_row = $this->searchPage->contentTable->getNodeRowByNid($second_nid);
        $second_row->checkStatus('To check');

        // Bulk-update the state of the first node to check bulk updates for one node only.
        /* @var $node_row SearchPageContentTableRow */
        $first_row = $this->searchPage->contentTable->getNodeRowByNid($first_nid);

        // Get the title of the nodes for later.
        $first_title = $first_row->links->linkTitle->text();
        $second_title = $second_row->links->linkTitle->text();

        $first_row->bulkActionCheckbox->check();
        $this->searchPage->bulkActions->selectAction->selectOptionByLabel('Set moderation state');

        // Verify that only the wanted options are shown in the select box for a
        // Chief Editor.
        $options = $this->searchPage->bulkActions->selectState->getOptions();
        $this->assertFalse(isset($options['draft']));
        $this->assertFalse(isset($options['scheduled']));
        $this->assertTrue(isset($options['published']));
        $this->assertTrue(isset($options['offline']));
        $this->assertTrue(isset($options['to_check']));
        $this->assertTrue(isset($options['needs_review']));

        $this->searchPage->bulkActions->selectState->selectOptionByLabel('Online');
        $this->searchPage->bulkActions->executeButton->click();
        $this->searchPage->checkArrival();
        $this->assertTextPresent($first_title);
        $this->assertTextNotPresent($second_title);
        $this->searchPage->bulkActions->buttonConfirm->click();
        $this->waitUntilTextIsPresent('Performed Set moderation state on 1 item.');

        /* @var $node_row SearchPageContentTableRow */
        $first_row = $this->searchPage->contentTable->getNodeRowByNid($first_nid);
        $first_row->checkStatus('Online');

        // Do it again but this time cancel the action to see if the state
        // remains unchanged.
        $first_row->bulkActionCheckbox->check();
        $this->searchPage->bulkActions->selectAction->selectOptionByLabel('Set moderation state');
        $this->searchPage->bulkActions->selectState->selectOptionByLabel('Offline');
        $this->searchPage->bulkActions->executeButton->click();
        $this->searchPage->checkArrival();
        $this->searchPage->bulkActions->buttonCancel->click();
        $this->searchPage->checkArrival();

        /* @var $node_row SearchPageContentTableRow */
        $first_row = $this->searchPage->contentTable->getNodeRowByNid($first_nid);
        $first_row->checkStatus('Online');

        /* @var $node_row SearchPageContentTableRow */
        $second_row = $this->searchPage->contentTable->getNodeRowByNid($second_nid);

        // Now check the bulk-update for multiple nodes.
        $first_row->bulkActionCheckbox->check();
        $second_row->bulkActionCheckbox->check();
        $this->searchPage->bulkActions->selectAction->selectOptionByLabel('Set moderation state');
        $this->searchPage->bulkActions->selectState->selectOptionByLabel('Offline');
        $this->searchPage->bulkActions->executeButton->click();
        $this->searchPage->checkArrival();
        $this->assertTextPresent($first_title);
        $this->assertTextPresent($second_title);
        $this->searchPage->bulkActions->buttonConfirm->click();
        $this->waitUntilTextIsPresent('Performed Set moderation state on');
        $this->searchPage->checkArrival();

        /* @var $node_row SearchPageContentTableRow */
        $first_row = $this->searchPage->contentTable->getNodeRowByNid($first_nid);
        $first_row->checkStatus('Offline');
        /* @var $node_row SearchPageContentTableRow */
        $second_row = $this->searchPage->contentTable->getNodeRowByNid($second_nid);
        $second_row->checkStatus('Offline');

        // Set the first node back to online to make sure an editor cannot
        // change the state of it trough bulk actions.
        $this->adminNodeViewPage->go($first_nid);
        $this->adminNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->adminNodeViewPage->checkArrival();

        // Now login as an editor and verify the permissions.
        $this->userSessionService->switchUser('Editor');

        // Create a third node to perform a delete on multiple items.
        $third_nid = $this->contentCreationService->createBasicPage();
        $this->searchPage->go();

        // Verify that for an editor, there is no checkbox in front of nodes
        // which are in the Online, Concept or To check state.
        /* @var $first_row SearchPageContentTableRow */
        $first_row = $this->searchPage->contentTable->getNodeRowByNid($first_nid);
        /* @var $second_row SearchPageContentTableRow */
        $second_row = $this->searchPage->contentTable->getNodeRowByNid($second_nid);
        /* @var $third_row SearchPageContentTableRow */
        $third_row = $this->searchPage->contentTable->getNodeRowByNid($third_nid);
        $third_title = $third_row->links->linkTitle->text();

        $this->assertFalse($first_row->bulkActionCheckbox);
        $this->assertTrue($second_row->bulkActionCheckbox->isDisplayed());
        $this->assertTrue($third_row->bulkActionCheckbox->isDisplayed());

        // Verify that only the wanted options are shown in the select box for
        // an Editor.
        $options = $this->searchPage->bulkActions->selectState->getOptions();
        $this->assertFalse(isset($options['draft']));
        $this->assertFalse(isset($options['scheduled']));
        $this->assertFalse(isset($options['published']));
        $this->assertFalse(isset($options['offline']));
        $this->assertTrue(isset($options['to_check']));
        $this->assertTrue(isset($options['needs_review']));

        // Now moderate all nodes to In review and verify that node 1 and 2 have
        // not changed but node 3 is in review now. Also verify that the
        // checkbox in front of the node in the in review state is no longer
        // displayed for an editor.
        $this->assertFalse($first_row->getBulkActionCheckbox());
        $second_row->bulkActionCheckbox->check();
        $third_row->bulkActionCheckbox->check();
        $this->searchPage->bulkActions->selectAll->check();
        $this->searchPage->bulkActions->selectAction->selectOptionByLabel('Set moderation state');
        $this->searchPage->bulkActions->selectState->selectOptionByLabel('To check');
        $this->searchPage->bulkActions->executeButton->click();
        $this->searchPage->checkArrival();

        // We first enter the config screen to choose an assignee.
        // Verify that only the wanted assignees are in the select.
        $assignees = $this->searchPage->bulkActions->selectAssignee->getOptions();
        $this->assertTrue(in_array('Assign to any', $assignees));
        $this->assertTrue(in_array('demo_editor', $assignees));
        $this->assertFalse(in_array('demo_chief_editor', $assignees));
        $this->assertFalse(in_array('demo', $assignees));
        $this->searchPage->bulkActions->buttonConfigCancel->click();
        $this->searchPage->checkArrival();

        // Prevent stale elements.
        /* @var $second_row SearchPageContentTableRow */
        $second_row = $this->searchPage->contentTable->getNodeRowByNid($second_nid);
        /* @var $third_row SearchPageContentTableRow */
        $third_row = $this->searchPage->contentTable->getNodeRowByNid($third_nid);

        $this->assertFalse($first_row->getBulkActionCheckbox());
        $second_row->bulkActionCheckbox->check();
        $third_row->bulkActionCheckbox->check();
        $this->searchPage->bulkActions->selectAction->selectOptionByLabel('Set moderation state');
        $this->searchPage->bulkActions->selectState->selectOptionByLabel('In review');
        $this->searchPage->bulkActions->executeButton->click();

        $assignees = $this->searchPage->bulkActions->selectAssignee->getOptions();
        $this->assertTrue(in_array('Assign to any', $assignees));
        $this->assertFalse(in_array('demo_editor', $assignees));
        $this->assertTrue(in_array('demo_chief_editor', $assignees));
        $this->assertTrue(in_array('demo', $assignees));

        // Select an assignee to actually assign the node to.
        $this->searchPage->bulkActions->selectAssignee->selectOptionByLabel('demo');
        $this->searchPage->bulkActions->buttonNext->click();
        $this->searchPage->checkArrival();

        // The title of the first node should not be shown.
        $this->assertTextNotPresent($first_title);
        $this->assertTextPresent($second_title);
        $this->assertTextPresent($third_title);
        $this->searchPage->bulkActions->buttonConfirm->click();
        $this->waitUntilTextIsPresent('Performed Set moderation state on');
        $this->searchPage->checkArrival();

        /* @var $node_row SearchPageContentTableRow */
        $third_row = $this->searchPage->contentTable->getNodeRowByNid($third_nid);
        $this->assertFalse($third_row->bulkActionCheckbox);
        $third_row->checkStatus('In review');

        // Verify that the assignee is set correctly.
        $this->adminNodeViewPage->go($third_nid);
        $this->adminNodeViewPage->nodeSummary->showAllMetadata();
        $assignee_metadata = $this->adminNodeViewPage->nodeSummary->getMetadata('workflow', 'assigned');
        $this->assertEquals('demo', $assignee_metadata['value']);

        // Login as chief editor.
        $this->userSessionService->switchUser('ChiefEditor');
        $this->searchPage->go();

        // Try to set the page responsible author trough bulk actions for a
        // node.
        $third_row = $this->searchPage->contentTable->getNodeRowByNid($third_nid);
        $third_row->bulkActionCheckbox->check();

        $this->searchPage->bulkActions->selectAction->selectOptionByLabel('Set page responsible author');
        $this->searchPage->bulkActions->selectResponsibleAuthor->selectOptionByLabel('demo');
        $this->searchPage->bulkActions->executeButton->click();
        $this->searchPage->checkArrival();
        $this->assertTextNotPresent($first_title);
        $this->assertTextNotPresent($second_title);
        $this->assertTextPresent($third_title);
        $this->searchPage->bulkActions->buttonConfirm->click();
        $this->waitUntilTextIsPresent('Performed Set page responsible author on 1 item.');

        // Verify that the page responsible author is set correctly.
        $this->adminNodeViewPage->go($third_nid);
        $this->adminNodeViewPage->nodeSummary->showAllMetadata();
        $responsible_author_metadata = $this->adminNodeViewPage->nodeSummary->getMetadata('created', 'page-responsible-author');
        $this->assertEquals('demo', $responsible_author_metadata['value']);
    }

    /**
     * Tests the "Set state" bulk action for "offline" and "archive" states.
     *
     * @dataProvider states
     *
     * @group workflow
     * @group bulkActions
     * @group regression
     */
    public function testNodeDepublishBulkActions($state)
    {
        // Create 4 nodes.
        $nids = array();
        for ($i = 0; $i < 4; $i++) {
            $nids[$i] = $this->contentCreationService->createBasicPage();

            // Publish the 2 last nodes.
            if ($i > 1) {
                $this->adminNodeViewPage->go($nids[$i]);
                $this->adminNodeViewPage->contextualToolbar->buttonPublish->click();
                $this->adminNodeViewPage->checkArrival();
            }
        }

        // Edit the last node to create a new draft revision.
        $this->editPage->go($nids[3]);
        $this->editPage->contextualToolbar->buttonSave->click();

        // Go to the content manager page and select all nodes.
        $this->searchPage->go();
        foreach ($nids as $nid) {
            $this->searchPage->contentTable->getNodeRowByNid($nid)->bulkActionCheckbox->check();
        }

        $this->searchPage->bulkActions->selectAction->selectOptionByLabel('Set moderation state');
        $this->searchPage->bulkActions->selectState->selectOptionByLabel($state);
        $this->searchPage->bulkActions->executeButton->click();
        $this->searchPage->checkArrival();
        $this->searchPage->bulkActions->buttonConfirm->click();
        $this->searchPage->checkArrival();

        // Verify that none of the nodes has a published revision and that the
        // status of the node is set to "0".
        $nodes = node_load_multiple($nids);

        foreach ($nodes as $node) {
            $this->assertEquals(0, $node->status);
            $this->assertEmpty($node->workbench_moderation['published']);
        }
    }

    /**
     * Asserts that a datepicker has both a month and a year select box.
     */
    public function assertDatepickerHasMonthAndYearSelectBoxes(Datepicker $datepicker, $message)
    {
        $this->assertTrue($datepicker->popup->hasMonthSelectBox() && $datepicker->popup->hasYearSelectBox(), $message);
    }
}
