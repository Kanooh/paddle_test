<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Comment\CommentManagerTest.
 */

namespace Kanooh\Paddle\App\Comment;

use Kanooh\Paddle\Apps\Comment;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleComment\CommentManagerPage\CommentManagerPage;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleComment\CommentManagerPage\CommentManagerPageTableRow;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleComment\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Comment\DeletePage\DeletePage;
use Kanooh\Paddle\Pages\Comment\EditPage\EditPage as CommentEditPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndNodeViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs test on the Comment overview page.
 *
 * @package Kanooh\Paddle\App\Comment
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class CommentManagerTest extends WebDriverTestCase
{
    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

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
     * @var CommentManagerPage
     */
    protected $commentManagerPage;

    /**
     * @var CommentEditPage
     */
    protected $commentEditPage;

    /**
     * @var DeletePage
     */
    protected $deletePage;

    /**
     * @var EditPage
     */
    protected $editPage;

    /**
     * @var FrontEndNodeViewPage
     */
    protected $frontEndNodeViewPage;

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
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->configurePage = new ConfigurePage($this);
        $this->commentEditPage = new CommentEditPage($this);
        $this->commentManagerPage = new CommentManagerPage($this);
        $this->editPage = new EditPage($this);
        $this->deletePage = new DeletePage($this);
        $this->frontEndNodeViewPage = new FrontEndNodeViewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Comment());

        // Log in as a site manager.
        $this->userSessionService->login('SiteManager');
    }

    /**
     * Tests the Comment overview page.
     */
    public function testCommentOverviewPage()
    {
        // First delete all comments from previous tests.
        $service = new CleanUpService($this);
        $service->deleteEntities('comment');

        // Enable commenting for basic and landing pages.
        $this->configurePage->go();
        $this->configurePage->form->typeBasicPage->check();
        $this->configurePage->form->typeLandingPage->check();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');

        // Create a basic page and a landing page.
        $nodes = array(
            'basic' => array(
                'title' => $this->alphanumericTestDataProvider->getValidValue(),
                'nid' => 0,
                'comments' => array(),
            ),
            'landing' => array(
                'title' => $this->alphanumericTestDataProvider->getValidValue(),
                'nid' => 0,
                'comments' => array(),
            )
        );
        $nodes['basic']['nid'] = $this->contentCreationService->createBasicPage($nodes['basic']['title']);
        $nodes['landing']['nid'] = $this->contentCreationService->createLandingPage(null, $nodes['landing']['title']);

        // Enable comment on both pages and publish them so they are accessible
        // for anonymous users.
        foreach ($nodes as $type => $node) {
            $this->editPage->go($node['nid']);
            $this->editPage->enableCommenting();
            $this->administrativeNodeViewPage->checkArrival();

            $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
            $this->administrativeNodeViewPage->checkArrival();

            // Add some comments now one from the logged-in user and one anonymous.
            $this->frontEndNodeViewPage->go($node['nid']);
            $body = $this->alphanumericTestDataProvider->getValidValue(50);
            $cid = $this->frontEndNodeViewPage->postComment($body);
            $nodes[$type]['comments'][$cid] = array(
                'name' => 'demo',
                'body' => $body,
                'anonymous' => false,
            );
        }

        // Now create the anonymous comments.
        $this->userSessionService->logout();
        foreach ($nodes as $type => $node) {
            $this->frontEndNodeViewPage->go($node['nid']);
            $body = $this->alphanumericTestDataProvider->getValidValue(50);
            $name = $this->alphanumericTestDataProvider->getValidValue(10);
            $cid = $this->frontEndNodeViewPage->postComment($body, $name);
            $nodes[$type]['comments'][$cid] = array(
                'name' => $name,
                'body' => $body,
                'anonymous' => true,
            );
        }

        $this->userSessionService->login('SiteManager');

        // Go on the Comment Manager page and make sure we find all the comments.
        $this->commentManagerPage->go();

        foreach ($nodes as $type => $node) {
            foreach ($node['comments'] as $cid => $comment) {
                  $expected_values = array(
                      'title' => $node['title'],
                      'name' => $comment['name'],
                      'anonymous' => $comment['anonymous'],
                  );
                  $this->assertComment($cid, $expected_values);
            }
        }

        // Check the exposed form filters.
        $this->commentManagerPage->exposedForm->published->selectOptionByLabel('Yes');
        $this->commentManagerPage->exposedForm->buttonApply->click();
        $this->commentManagerPage->waitUntilPageIsLoaded();

        $this->assertEquals(2, $this->commentManagerPage->commentTable->getNumberOfRows(true));

        foreach ($nodes as $type => $node) {
            foreach ($node['comments'] as $cid => $comment) {
                if (!$comment['anonymous']) {
                    $expected_values = array(
                        'title' => $node['title'],
                        'name' => $comment['name'],
                        'anonymous' => $comment['anonymous'],
                    );
                    $this->assertComment($cid, $expected_values);
                } else {
                    $this->assertFalse($this->commentManagerPage->commentTable->getCommentRowByCid($cid));
                }
            }
        }

        // Apply additional filter.
        $this->commentManagerPage->exposedForm->nodeContentType->selectOptionByLabel('Basic page');
        $this->commentManagerPage->exposedForm->buttonApply->click();
        $this->commentManagerPage->waitUntilPageIsLoaded();
        $this->assertEquals(1, $this->commentManagerPage->commentTable->getNumberOfRows(true));
        foreach ($nodes['basic']['comments'] as $cid => $comment) {
            if (!$comment['anonymous']) {
                $expected_values = array(
                    'title' => $nodes['basic']['title'],
                    'name' => $comment['name'],
                    'anonymous' => $comment['anonymous'],
                );
                $this->assertComment($cid, $expected_values);
            } else {
                $this->assertFalse($this->commentManagerPage->commentTable->getCommentRowByCid($cid));
            }
        }
        foreach ($nodes['landing']['comments'] as $cid => $comment) {
            $this->assertFalse($this->commentManagerPage->commentTable->getCommentRowByCid($cid));
        }

        // Publish one of the anonymous comments to check the bulk actions.
        $this->commentManagerPage->exposedForm->nodeContentType->selectOptionByLabel('- Any -');
        $this->commentManagerPage->exposedForm->published->selectOptionByLabel('- Any -');
        $this->commentManagerPage->exposedForm->buttonApply->click();
        $this->commentManagerPage->waitUntilPageIsLoaded();
        $this->assertEquals(4, $this->commentManagerPage->commentTable->getNumberOfRows(true));

        $comments = array_keys($nodes['basic']['comments']);
        /** @var CommentManagerPageTableRow $row */
        $row = $this->commentManagerPage->commentTable->getCommentRowByCid($comments[1]);
        $row->bulkActionCheckbox->check();
        $this->commentManagerPage->bulkActions->selectAction->selectOptionByLabel('Publish comment');
        $this->commentManagerPage->bulkActions->executeButton->click();
        $this->commentManagerPage->checkArrival();
        $this->commentManagerPage->bulkActions->buttonConfirm->click();
        $this->waitUntilTextIsPresent('Performed Publish comment on 1 item.');
        $this->commentManagerPage->checkArrival();

        $this->commentManagerPage->exposedForm->published->selectOptionByLabel('Yes');
        $this->commentManagerPage->exposedForm->buttonApply->click();
        $this->commentManagerPage->waitUntilPageIsLoaded();

        // Only test that the only unpublished comment is not shown since we
        // check the number of comments.
        $this->assertEquals(3, $this->commentManagerPage->commentTable->getNumberOfRows(true));
        foreach ($nodes['landing']['comments'] as $cid => $comment) {
            if ($comment['anonymous']) {
                $this->assertFalse($this->commentManagerPage->commentTable->getCommentRowByCid($cid));
            }
        }

        // Check the Un-publishing of comments through Bulk actions.
        $this->commentManagerPage->exposedForm->published->selectOptionByLabel('Yes');
        $this->commentManagerPage->exposedForm->buttonApply->click();
        $this->commentManagerPage->waitUntilPageIsLoaded();

        $this->commentManagerPage->bulkActions->selectAll->check();
        $this->commentManagerPage->bulkActions->selectAction->selectOptionByLabel('Unpublish comment');
        $this->commentManagerPage->bulkActions->executeButton->click();
        $this->commentManagerPage->checkArrival();
        $this->commentManagerPage->bulkActions->buttonConfirm->click();
        $this->waitUntilTextIsPresent('Performed Unpublish comment on 3 items.');
        $this->commentManagerPage->checkArrival();
        $this->assertEquals(0, $this->commentManagerPage->commentTable->getNumberOfRows(true));

        $this->commentManagerPage->exposedForm->published->selectOptionByLabel('- Any -');
        $this->commentManagerPage->exposedForm->nodeContentType->selectOptionByLabel('- Any -');
        $this->commentManagerPage->exposedForm->buttonApply->click();
        $this->commentManagerPage->waitUntilPageIsLoaded();
        $this->assertEquals(4, $this->commentManagerPage->commentTable->getNumberOfRows(true));

        // Finally check deleting of comments through Bulk actions.
        $this->commentManagerPage->bulkActions->selectAll->check();
        $this->commentManagerPage->bulkActions->selectAction->selectOptionByLabel('Delete item');
        $this->commentManagerPage->bulkActions->executeButton->click();
        $this->commentManagerPage->checkArrival();
        $this->commentManagerPage->bulkActions->buttonConfirm->click();
        $this->waitUntilTextIsPresent('Performed Delete item on 4 items.');
        $this->commentManagerPage->checkArrival();
        $this->assertEquals(0, $this->commentManagerPage->commentTable->getNumberOfRows(true));

        // Restore the default values.
        $this->configurePage->go();
        $this->configurePage->form->typeBasicPage->uncheck();
        $this->configurePage->form->typeLandingPage->uncheck();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');
    }

    /**
     * Tests editing and deleting of comments.
     */
    public function testEditDeleteComment()
    {
        // Enable commenting for basic page.
        $this->configurePage->go();
        $this->configurePage->form->typeBasicPage->check();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');

        // Create a node to test on.
        $nid = $this->contentCreationService->createBasicPage();

        // Enable commenting for this node.
        $this->editPage->go($nid);
        $this->editPage->enableCommenting();
        $this->administrativeNodeViewPage->checkArrival();

        // Post the comment.
        $this->frontEndNodeViewPage->go($nid);
        $body = $this->alphanumericTestDataProvider->getValidValue(50);
        $cid = $this->frontEndNodeViewPage->postComment($body);

        // Try editing the comment.
        $this->commentManagerPage->go();
        /** @var CommentManagerPageTableRow $row */
        $row = $this->commentManagerPage->commentTable->getCommentRowByCid($cid);
        $row->editLink->click();
        $this->commentEditPage->checkArrival();

        // Edit something we can easily assert on the Comment manager page.
        // There is no point of testing the full comment edit since this is
        // tested in the Core Comment module itself.
        $this->commentEditPage->commentForm->openAdministrationFieldset();
        $this->commentEditPage->commentForm->name->fill('demo_editor');
        $this->commentEditPage->commentForm->save->click();

        // Check that the change in the comment was saved.
        $this->commentManagerPage->checkArrival();
        /** @var CommentManagerPageTableRow $row */
        $row = $this->commentManagerPage->commentTable->getCommentRowByCid($cid);
        $this->assertEquals('demo_editor', $row->author);

        // Now delete the comment. Again no extensive testing is needed as this
        // is core functionality.
        $row->deleteLink->click();
        $this->deletePage->checkArrival();
        $this->deletePage->delete->click();

        // Check that the comment was deleted.
        $this->commentManagerPage->checkArrival();
        /** @var CommentManagerPageTableRow $row */
        $this->assertFalse($this->commentManagerPage->commentTable->getCommentRowByCid($cid));

        // Restore the default values.
        $this->configurePage->go();
        $this->configurePage->form->typeBasicPage->uncheck();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');
    }

    /**
     * Asserts that a comment row is what we expect.
     *
     * @param $cid
     *   The id of the comment to check.
     * @param array $expected_values
     *   The expected values of the comment:
     *     - title: the title of the node to which the comment is attached
     *     - name: the name of the author of the comment
     *     - anonymous: indicates whether the author of the comment is an
     *       anonymous user or not
     */
    protected function assertComment($cid, array $expected_values)
    {
        /** @var CommentManagerPageTableRow $row */
        $row = $this->commentManagerPage->commentTable->getCommentRowByCid($cid);
        $this->assertEquals($expected_values['title'], $row->nodeTitle);
        $this->assertEquals($expected_values['name'], $row->author);
        $expected_status = $expected_values['anonymous'] ? 'No' : 'Yes';
        $this->assertTrue($row->checkStatus($expected_status), "Comment $cid has status $expected_status");

        $db_comment = comment_load($cid);
        $expected_time = format_date($db_comment->changed, 'short', 'paddle_core_date_long');
        $this->assertEquals($expected_time, $row->lastModified);
    }
}
