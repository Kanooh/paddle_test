<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\Base\UnpublishLiveRevisionTestBase.
 */

namespace Kanooh\Paddle\Core\ContentType\Base;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Element\Toolbar\ToolbarButtonNotPresentException;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditSimpleContactPagePage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage as NodeEditPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the ability to unpublish the live revision of a node.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class UnpublishLiveRevisionTestBase extends WebDriverTestCase
{
    /**
     * The message shown when a page is being locked.
     */
    const CONTENT_LOCK_LOCKING_MESSAGE = 'This document is now locked against simultaneous editing.';

    /**
     * The message shown when an action cannot be performed on a locked page.
     */
    const CONTENT_LOCK_LOCKED_MESSAGE = 'This document is locked for editing';

    /**
     * The message shown when a lock is broken.
     */
    const CONTENT_LOCK_RELEASE_MESSAGE = 'The editing lock held by';

    /**
     * The message shown when a live revision is unpublished.
     */
    const PADDLE_CONTENT_MANAGER_UNPUBLISH_MESSAGE = 'The live revision of this content has been unpublished.';

    /**
     * The page class that allows to create landing pages.
     *
     * @var AddPage addContentPage
     */
    protected $addContentPage;

    /**
     * The administrative node view.
     *
     * @var AdministrativeNodeViewPage $administrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The page class that allows to edit simple contact pages.
     *
     * @var EditSimpleContactPagePage editSimpleContactPagePage
     */
    protected $editSimpleContactPagePage;

    /**
     * The frontpage.
     *
     * @var FrontPage $frontPage
     */
    protected $frontPage;

    /**
     * The node edit page.
     *
     * @var NodeEditPage $nodeEditPage
     */
    protected $nodeEditPage;

    /**
     * The random data generator.
     *
     * @var Random $random
     */
    protected $random;

    /**
     * The user session service.
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

        // Instantiate the Pages that will be visited in the test.
        $this->addContentPage = new AddPage($this);
        $this->editSimpleContactPagePage = new EditSimpleContactPagePage($this);
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->frontPage = new FrontPage($this);
        $this->nodeEditPage = new NodeEditPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Instantiate helper classes.
        $this->random = new Random();

        // Go to the login page and log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->contentCreationService->cleanUp($this);
        parent::tearDown();
    }

    /**
     * Test the unpublishing of the live revision of a node.
     *
     * @see https://one-agency.atlassian.net/browse/KANWEBS-1567
     *
     * @group workflow
     */
    public function testUnpublishLiveRevision()
    {
        // Create a new page of the given type.
        $nid = $this->setupNode();
        $this->administrativeNodeViewPage->go($nid);

        $this->assertModerationState('Concept');

        // Check that the and 'Offline' button is not available.
        // The node has not yet been published, so it cannot be unpublished yet.
        try {
            $this->administrativeNodeViewPage->contextualToolbar->checkButtons(array('Offline'));
            $this->fail('The "Offline" button is not present on a newly created, unpublished, page.');
        } catch (ToolbarButtonNotPresentException $e) {
        }

        // Publish the page.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();

        $this->assertModerationState('Online');

        // Check that the 'Offline' button is now available. When the current
        // revision is published the editors should use moderation to unpublish
        // the node. Also the 'Publish' button should not be available since the
        // node is already published.
        $this->administrativeNodeViewPage->contextualToolbar->checkButtons(array('Offline'));
        try {
            $this->administrativeNodeViewPage->contextualToolbar->checkButtons(array('Publish'));
            $this->fail('The "Publish" button is not present on a page of which the current revision is published.');
        } catch (ToolbarButtonNotPresentException $e) {
        }

        // Create a new revision of the page, with a new title so we can
        // recognize which revision is shown.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageProperties->click();
        $this->nodeEditPage->checkArrival();

        // @todo Rework this when we have a Form to interact with.
        // @see https://one-agency.atlassian.net/browse/KANWEBS-1345
        $this->nodeEditPage->body->setBodyText($this->random->name(16));

        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Check that the moderation state has changed to 'Concept'.
        $this->assertModerationState('Concept');

        // Check that the 'Offline' button is now available, as there is now a
        // live revision which is older than the current revision and the
        // editors should be able to unpublish it without affecting the current
        // revision.
        // The 'Publish' button should be there now, as this can be used to
        // publish the current revision which then replaces the older live
        // revision.
        $this->administrativeNodeViewPage->contextualToolbar->checkButtons(array('Publish', 'Offline'));

        // Log in as editor and lock the page.
        $this->userSessionService->switchUser('Editor');
        $this->administrativeNodeViewPage->go($nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageProperties->click();
        $this->nodeEditPage->checkArrival();
        $this->assertTextPresent(self::CONTENT_LOCK_LOCKING_MESSAGE);

        // Clear all browser cookies instead of logging the user out via the
        // logout page, since this would break the lock.
        $this->userSessionService->clearCookies();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();

        // Log in as chief editor again and try to unpublish the live revision.
        $this->userSessionService->login('ChiefEditor');
        $this->administrativeNodeViewPage->go($nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonOffline->click();

        // Check that a message appears informing that the content is locked.
        $this->waitUntilTextIsPresent(self::CONTENT_LOCK_LOCKED_MESSAGE);

        // Check that the page status has not changed.
        $this->assertModerationState('Concept');

        // Check that the buttons are still in the same state.
        $this->administrativeNodeViewPage->contextualToolbar->checkButtons(array('Publish', 'Offline'));

        // Break the lock.
        $xpath = '//div[@id = "messages"]//a[contains(@href, "/admin/content/content_lock/release/' . $nid . '")]';
        $this->byXPath($xpath)->click();
        $this->waitForText(self::CONTENT_LOCK_RELEASE_MESSAGE);

        // Check that we are still on the administrative node view.
        $this->administrativeNodeViewPage->checkArrival();

        // Let's try again. It should work this time.
        $this->administrativeNodeViewPage->contextualToolbar->buttonOffline->click();
        $this->waitForText(self::PADDLE_CONTENT_MANAGER_UNPUBLISH_MESSAGE);

        // The current revision should not change. Should still be in 'Concept'.
        $this->assertModerationState('Concept');

        // The Publish button should still be available, so that the current
        // revision can still be published.
        $this->administrativeNodeViewPage->contextualToolbar->checkButtons(array('Publish'));

        // The 'Offline' button should now be hidden, as there is no live
        // revision anymore.
        try {
            $this->administrativeNodeViewPage->contextualToolbar->checkButtons(array('Offline'));
            $this->fail('The "Offline" button is not present when there is no published revision.');
        } catch (ToolbarButtonNotPresentException $e) {
        }
    }

    /**
     * Check if the moderation state is set to the expected value.
     *
     * @param string $state
     *   The expected moderation state.
     */
    public function assertModerationState($state)
    {
        $this->administrativeNodeViewPage->nodeSummary->showAllMetadata();
        $actual_state = $this->administrativeNodeViewPage->nodeSummary->getMetadata('workflow', 'status');
        $this->assertEquals($state, $actual_state['value']);
    }
}
