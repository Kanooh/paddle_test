<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\OverviewPage\Common\ContentDiscoveryTest.
 */

namespace Kanooh\Paddle\Core\ContentType\OverviewPage\Common;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Core\ContentType\Base\ContentDiscoveryTestBase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ContentDiscoveryTest extends ContentDiscoveryTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setupNode($title = null)
    {
        return $this->contentCreationService->createOverviewPage($title);
    }


    /**
     * Tests the actions on the content discovery tests.
     *
     * @dataProvider userDataProvider
     * @group contentDiscoveryTestBase
     * @group contentType
     * @group workflow
     */
    public function testContentDiscoveryActions($user)
    {
        // Log in as the desired user.
        $this->userSessionService->login($user);

        // Create a node.
        $nid = $this->setupNode();

        // Go to the content discovery tabs and verify that the actions are displayed
        // on all discovery tabs.
        $this->searchPage->go();
        $node_row = $this->searchPage->contentTable->getNodeRowByNid($nid);
        $node_row->links->checkLinks(array('PageProperties', 'PageLayout', 'FrontView', 'AdminView'));
        $node_row->links->checkLinksNotPresent(array('Delete'));

        // Click all actions and verify they have the wanted behaviour.
        // Click the front end view link.
        $node_row->links->linkFrontView->click();
        $this->frontEndNodeViewPage->checkArrival();
        $this->searchPage->go();

        // Click the backend view link.
        $node_row = $this->searchPage->contentTable->getNodeRowByNid($nid);
        $node_row->links->linkAdminView->click();
        $this->adminNodeViewPage->checkArrival();
        $this->searchPage->go();

        // Click the page properties link.
        $node_row->links->linkPageProperties->click();
        $this->nodeEditPage->checkArrival();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->searchPage->go();

        // Click the page layout link.
        $node_row->links->linkPageLayout->click();
        $this->getLayoutPage()->checkArrival();
        $this->getLayoutPage()->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
        $this->searchPage->go();

        // Run the tests for all content discovery tabs for moderated nodes.
        $states = array(
            'Concept' => null,
            'To check' => array('buttonToEditor', 'buttonToAnyEditor'),
            'In review' => array('buttonToChiefEditor', 'buttonToAnyChiefEditor'),
            'Online' => 'buttonPublish',
            'Offline' => 'buttonOffline',
            'Scheduled for publication' => null,
            'Scheduled for depublication' => null,
        );

        foreach ($states as $state => $moderate_button) {
            $test_case = $this;

            // Schedule the node to be published on another date. Always use the
            // Chief Editor user for this, because a regular user is not
            // allowed to finalize the scheduling.
            if ($state == 'Scheduled for publication') {
                $callable = new SerializableClosure(
                    function () use ($test_case, $nid) {
                        $test_case->scheduleNode($nid);
                    }
                );
                $this->userSessionService->runAsUser('ChiefEditor', $callable);
            }

            // Moderate the node to the new state. Always use the Chief Editor
            // user for this, because a regular user is not allowed to perform
            // certain state transitions.
            if (!empty($moderate_button)) {
                $callable = new SerializableClosure(
                    function () use ($test_case, $nid, $moderate_button) {
                        $test_case->adminNodeViewPage->go($nid);
                        // Sometimes we have to click multiple buttons before
                        // we get to the button that we actually want to click.
                        // Example: Click the "To chief editor" button to reveal
                        // the "Assign to x" button.
                        if (is_array($moderate_button)) {
                            foreach ($moderate_button as $btn) {
                                $test_case->adminNodeViewPage->contextualToolbar->{$btn}->click();
                            }
                        } else {
                            $test_case->adminNodeViewPage->contextualToolbar->{$moderate_button}->click();
                        }
                        $test_case->adminNodeViewPage->checkArrival();
                    }
                );
                $test_case->userSessionService->runAsUser('ChiefEditor', $callable);
            }

            // Go to the search page.
            $this->searchPage->go();
            $node_row = $this->searchPage->contentTable->getNodeRowByNid($nid);
            // The node is never really published so not possible to test this
            // specific case here. This is tested in simpletest anyway.
            if ($state == 'Scheduled for depublication') {
                $this->assertTrue($node_row->checkStatus('Scheduled for publication'));
            }

            // If the editor is supposed to be able to edit the node in its
            // current state or not.
            if ('Editor' == $user) {
                $can_edit_node = !in_array($state, array('In review'));
                $node_row->links->checkLinksNotPresent(array('Delete'));
            } else {
                $can_edit_node = true;
            }

            try {
                if ($can_edit_node) {
                    $node_row->links->checkLinks(array('PageProperties', 'PageLayout', 'FrontView', 'AdminView'));
                } else {
                    $node_row->links->checkLinks(array('FrontView', 'AdminView'));
                    $node_row->links->checkLinksNotPresent(array('PageProperties', 'PageLayout'));
                }
            } catch (\Exception $e) {
                $this->fail("Content discovery, user $user" . $e->getMessage());
            }

            // Click all actions and verify they have the wanted behaviour.
            // Click the front end view link.
            $node_row->links->linkFrontView->click();
            $this->frontEndNodeViewPage->checkArrival();
            $this->searchPage->go();

            // Click the backend view link.
            $node_row = $this->searchPage->contentTable->getNodeRowByNid($nid);
            $node_row->links->linkAdminView->click();
            $this->adminNodeViewPage->checkArrival();
            $this->searchPage->go();

            // Click the page properties link.
            if ($can_edit_node) {
                $node_row->links->linkPageProperties->click();
                $this->nodeEditPage->checkArrival();
                $this->nodeEditPage->contextualToolbar->buttonBack->click();
                $this->acceptAlert();
                $this->searchPage->checkArrival();

                // Click the page layout link.
                $node_row->links->linkPageLayout->click();
                $this->getLayoutPage()->checkArrival();
                $this->getLayoutPage()->contextualToolbar->buttonBack->click();
                $this->acceptAlert();
                $this->searchPage->checkArrival();
            }
        }
    }
}
