<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\Base\NodeRevisionsTestBase.
 */

namespace Kanooh\Paddle\Core\ContentType\Base;

use Kanooh\Paddle\Pages\Admin\ContentManager\Node\DiffPage\DiffPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\RevertPage\RevertPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\RevisionsPage\RevisionsPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\RevisionsPage\RevisionsPageTableRow;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Element\Links\LinkNotPresentException;
use Kanooh\Paddle\Pages\Element\Messages\Messages;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the node revisions.
 *
 * @group revisions
 */
abstract class NodeRevisionsTestBase extends WebDriverTestCase
{
    /**
     * The alphanumeric test data generator.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * Node edit page.
     *
     * @var EditPage
     */
    protected $editPage;

    /**
     * Node diff page.
     *
     * @var DiffPage
     */
    protected $diffPage;

    /**
     * The Drupal messages displayed on a page.
     *
     * @var Messages messages
     */
    protected $messages;

    /**
     * @var RevertPage
     */
    protected $revertPage;

    /**
     * Node revisions page.
     *
     * @var RevisionsPage
     */
    protected $revisionsPage;

    /**
     * @var AppService
     */
    protected $service;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * Node view page.
     *
     * @var ViewPage
     */
    protected $viewPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->diffPage = new DiffPage($this);
        $this->editPage = new EditPage($this);
        $this->messages = new Messages($this);
        $this->revertPage = new RevertPage($this);
        $this->revisionsPage = new RevisionsPage($this);
        $this->viewPage = new ViewPage($this);

        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->service = new AppService($this, $this->userSessionService);

        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Creates a node of the content type that is being tested.
     *
     * @param string $title
     *   Optional title for the node. If omitted a random title will be used.
     *
     * @return int
     *   The node ID of the node that was created.
     */
    abstract public function setupNode($title = null);

    /**
     * Tests the node revisions button.
     *
     * @group revisions
     */
    public function testRevisionsButton()
    {
        // Create the node.
        $nid = $this->setupNode();
        $this->viewPage->go($nid);

        // Assert that the revisions button is not shown as we don't have
        // any revisions yet.
        $this->assertFalse($this->checkRevisionLinkPresent());

        // Publish the node. The revision link must not be there yet.
        $this->viewPage->contextualToolbar->buttonPublish->click();
        $this->viewPage->checkArrival();
        $this->assertFalse($this->checkRevisionLinkPresent());

        // Edit the node to create a new revision.
        $this->viewPage->contextualToolbar->buttonPageProperties->click();
        $this->editPage->checkArrival();
        $this->editPage->body->setBodyText($this->alphanumericTestDataProvider->getValidValue());
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->viewPage->checkArrival();

        // Assert that the revision button is in place now.
        $this->assertTrue($this->checkRevisionLinkPresent());
        $this->viewPage->revisionsLink->click();

        // Verify that the contextual actions are present on the revisions page.
        $this->revisionsPage->checkArrival();
        $this->revisionsPage->contextualToolbar->buttonBack->click();
        $this->viewPage->checkArrival();
        $this->viewPage->revisionsLink->click();
        $this->revisionsPage->checkArrival();
        $this->revisionsPage->contextualToolbar->buttonCompare->click();
        $this->diffPage->checkArrival();

        // Verify that the contextual actions are present on the diff page.
        $this->diffPage->contextualToolbar->buttonRevisions->click();
        $this->revisionsPage->checkArrival();
    }

    /**
     * Tests for the general fields shown on the diff page.
     *
     * @group revisions
     */
    public function testGeneralDiffFields()
    {
        // Create the node.
        $nid = $this->setupNode();

        // Go to the node edit page and fill out the SEO fields and the creation
        // date field.
        $this->editPage->go($nid);

        // Generate some values.
        $seo_title = $this->alphanumericTestDataProvider->getValidValue();
        $seo_description = $this->alphanumericTestDataProvider->getValidValue();

        // Fill out the needed fields.
        $this->editPage->seoTitleField->value($seo_title);
        $this->editPage->seoDescriptionField->value($seo_description);

        // Also fill out the body field for easy testing.
        $body = $this->alphanumericTestDataProvider->getValidValue();
        $this->editPage->body->setBodyText($body);

        // Save the node and moderate it to another state.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->viewPage->checkArrival();

        $this->assertTextPresent($body);
        $this->viewPage->contextualToolbar->buttonToEditor->click();
        $this->viewPage->checkArrival();

        // Get the revisions for this node.
        $revisions = node_revision_list(node_load($nid));
        $revisions = array_slice($revisions, 0, 2, true);
        $vids = array_keys($revisions);

        // Test the view link for a revision.
        $this->revisionsPage->go($nid);
        $this->assertFalse($this->revisionsPage->checkClassPresent('paddle-social-media-share-button'));
        $this->revisionsPage->table->isPresent();
        /** @var RevisionsPageTableRow $vid_row */
        $vid_row = $this->revisionsPage->table->getRevisionRowByVid($vids[1]);
        $this->assertTrue($vid_row->links->linkView->displayed());

        $expected_url = 'node/' . $nid . '/revisions/' . $vids[1] . '/view';
        $this->assertContains($expected_url, $vid_row->links->linkView->attribute('href'));

        // Make sure the "Edited by !user" text is not showing.
        $this->assertTextNotPresent('Edited by');

        // Go to the diff page and verify that the fields are shown.
        $this->diffPage->go(array($nid, $vids[1], $vids[0]));
        $this->assertFalse($this->diffPage->checkClassPresent('paddle-social-media-share-button'));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent($seo_title));
        $this->assertTrue($this->diffPage->checkExactTextAddedPresent($seo_description));

        // Make sure the "Edited by !user" text is not showing.
        $this->assertTextNotPresent('Edited by');

        // Run the tests for the revert.
        $this->assertTextPresent('REVERT THIS VERSION');
        $this->diffPage->getRevertLink($nid, $vids[1])->click();
        $this->revertPage->checkArrival();

        // Check the canceling action.
        $this->revertPage->buttonCancel->click();
        $this->revisionsPage->checkArrival();

        // Now actually revert.
        $this->diffPage->go(array($nid, $vids[1], $vids[0]));
        $this->diffPage->getRevertLink($nid, $vids[1])->click();
        $this->revertPage->checkArrival();
        $this->assertFalse($this->revertPage->checkClassPresent('paddle-social-media-share-button'));
        $this->revertPage->buttonRevert->click();
        $this->revisionsPage->checkArrival();

        $this->viewPage->go($nid);
        $this->assertTextNotPresent($body);
        $status_metadata = $this->viewPage->nodeSummary->getMetadata('general', 'status');
        $this->assertEquals('Concept', $status_metadata['value']);

        // Test the view link for a revision.
        $this->revisionsPage->go($nid);
        $this->revisionsPage->table->isPresent();
        /** @var RevisionsPageTableRow $vid_row */
        $vid_row = $this->revisionsPage->table->getRevisionRowByVid($vids[1]);
        $this->assertTrue($vid_row->links->linkView->displayed());

        $expected_url = 'node/' . $nid . '/revisions/' . $vids[1] . '/view';
        $this->assertContains($expected_url, $vid_row->links->linkView->attribute('href'));

        // After a revert, we have a new revision. Reload the revisions list.
        $revisions = node_revision_list(node_load($nid));
        // Take in account the last 3 revisions now.
        $revisions = array_slice($revisions, 0, 3, true);
        $vids = array_keys($revisions);

        // Go to the latest revision page.
        $this->diffPage->go(array($nid, $vids[1], $vids[0]));

        // Check that the previous link is present and goes to the correct page.
        $this->assertTextPresent('Previous difference');
        $this->diffPage->navigationLinks->linkPrevious->click();
        $this->diffPage->checkArrival();
        $this->assertEquals(
            array(
                $nid,
                $vids[2],
                $vids[1],
            ),
            $this->diffPage->getPathArguments()
        );

        // Verify that the next link is present and goes to the correct page.
        $this->assertTextPresent('Next difference');
        $this->diffPage->navigationLinks->linkNext->click();
        $this->diffPage->checkArrival();
        $this->assertEquals(
            array(
                $nid,
                $vids[1],
                $vids[0],
            ),
            $this->diffPage->getPathArguments()
        );
    }

    /**
     * Checks the presence of the revision link.
     *
     * @return bool
     *   If the revision link is present or not.
     */
    protected function checkRevisionLinkPresent()
    {
        // Try to retrieve the property. If an exception is raised,
        // the link is not present.
        try {
            $this->viewPage->revisionsLink;
            return true;
        } catch (LinkNotPresentException $e) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->contentCreationService->cleanUp($this);
        parent::tearDown();
    }
}
