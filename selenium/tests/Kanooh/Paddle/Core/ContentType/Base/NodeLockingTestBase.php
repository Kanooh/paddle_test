<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\Base\NodeLockingTestBase.
 */

namespace Kanooh\Paddle\Core\ContentType\Base;

use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\LandingPageViewPage;
use Kanooh\Paddle\Pages\Admin\Content\ContentLockPage\ContentLockPage;
use Kanooh\Paddle\Pages\Admin\DashboardPage\DashboardPage;
use Kanooh\Paddle\Pages\Element\Messages\Messages;
use Kanooh\Paddle\Pages\Element\Toolbar\DropdownButton;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\PaddlePage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\MultilingualService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;
use PHPUnit_Extensions_Selenium2TestCase_Element;

/**
 * Tests content locking while editing.
 */
abstract class NodeLockingTestBase extends WebDriverTestCase
{

    /**
     * The 'Add' page of the Paddle Content Manager module.
     *
     * @var AddPage $addContentPage
     */
    protected $addContentPage;

    /**
     * The administrative node view of a landing page.
     *
     * @var LandingPageViewPage $administrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The page that lists all content that is locked.
     *
     * @var ContentLockPage $contentLockPage
     */
    protected $contentLockPage;

    /**
     * The administation dashboard page.
     *
     * @var DashboardPage $dashboardPage
     */
    protected $dashboardPage;

    /**
     * The Drupal messages displayed on a page.
     *
     * @var Messages messages
     */
    protected $messages;

    /**
     * The pages on which there should be locking.
     *
     * @var array $testPages
     */
    protected $testPages;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

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
     *
     */
    protected function pageLayoutPage()
    {
        return new LayoutPage($this);
    }

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Instantiate the Pages that will be visited in the test.
        $this->addContentPage = new AddPage($this);
        $this->administrativeNodeViewPage = new LandingPageViewPage($this);
        $this->contentLockPage = new ContentLockPage($this);
        $this->dashboardPage = new DashboardPage($this);
        $this->messages = new Messages($this);

        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        $this->testPages = array(
            'PageProperties' => new EditPage($this),
            'PageLayout' => $this->pageLayoutPage(),
        );
    }

    /**
     * Executes a callback in a new window.
     *
     * Opens a new window, executes the callback, closes the window and puts
     * back the focus in the initial window.
     *
     * @param callable $callback
     */
    public function inNewWindow($callback)
    {
        $main_window_handle = $this->windowHandle();

        // @see https://github.com/sebastianbergmann/phpunit-selenium/issues/160
        $this->execute(
            array(
                'script' => 'window.open("' . $this->base_url . '");',
                'args' => array(),
            )
        );

        $handles = $this->windowHandles();
        $new_window = array_pop($handles);
        $this->window($new_window);

        $callback($this);

        $this->closeWindow();

        $this->window($main_window_handle);
    }

    /**
     * Test the locking and releasing of node.
     *
     * @group contentType
     * @group editing
     * @group nodeLockingTestBase
     * @group contentLocking
     */
    public function testNodeLocking()
    {
        $this->userSessionService->login('ChiefEditor');

        $lock_message =
            "This document is now locked against simultaneous editing.";

        // Create a new page of the given type.
        $nid = $this->setupNode();
        $node = node_load($nid);
        $node_title = $node->title;

        $this->administrativeNodeViewPage->go($nid);

        // Go to the "Page properties" page (node edit) and to "Page layout"
        // (panels edit) page to check the locking.
        /** @var PaddlePage $page */
        foreach ($this->testPages as $button_name => $page) {
            $this->administrativeNodeViewPage->checkArrival();
            $this->administrativeNodeViewPage->contextualToolbar->{'button' . $button_name}->click();
            $page->checkArrival();

            // Open an additional window to verify the page was locked.
            // We can not verify it in the current window because navigating
            // to another page in it will effectively release the lock.
            $this->assertNodeLockedInNewWindow($node_title);
            // Check that the lock message is present.
            $status_messages = $this->messages->statusMessages();
            $this->assertMessagesContainsMessage($status_messages, $lock_message);

            // Go away without pressing any buttons.
            $this->url('/admin');

            // An alert will ask us if we are sure we want to leave.
            // This should also release the lock.
            $this->acceptAlert();

            // Wait until we arrive on the Dashboard before continuing.
            $this->dashboardPage->checkArrival();

            // Check if the node is not locked any longer.
            $this->assertNodeNotLocked($node_title);

            // Go back to page we were testing, click cancel and check if the
            // lock is broken as well.
            $this->administrativeNodeViewPage->go($nid);
            $this->administrativeNodeViewPage->contextualToolbar->{'button' . $button_name}->click();
            $page->checkArrival();
            $this->assertNodeLockedInNewWindow($node_title);
            $status_messages = $this->messages->statusMessages();
            $this->assertMessagesContainsMessage($status_messages, $lock_message);
            $page->contextualToolbar->buttonBack->click();
            $this->acceptAlert();

            // Check that we are redirected to the administrative node view.
            $this->administrativeNodeViewPage->checkArrival();

            $this->assertNodeNotLocked($node_title);

            // Go back to page we were testing, click "Save" and check if the lock is broken as
            // well.
            $this->administrativeNodeViewPage->go($nid);
            $this->administrativeNodeViewPage->contextualToolbar->{'button' . $button_name}->click();
            $page->checkArrival();
            $this->assertNodeLockedInNewWindow($node_title);
            // Check that the lock message is present.
            $status_messages = $this->messages->statusMessages();
            $this->assertMessagesContainsMessage($status_messages, $lock_message);
            $page->contextualToolbar->buttonSave->click();
            $this->administrativeNodeViewPage->checkArrival();
            $this->assertNodeNotLocked($node_title);
            $this->administrativeNodeViewPage->go($nid);
        }

        // Check that other users do not have access to the node while it is
        // locked. Go to the node edit to lock it.
        $this->administrativeNodeViewPage->go($nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageProperties->click();
        $this->assertNodeLockedInNewWindow($node_title);

        // We can not log out, logging out will release all locks the logged out
        // user holds. Instead clear all browser cookies.
        $this->userSessionService->clearCookies();

        $this->url('/');
        $this->acceptAlert();
        $this->waitUntil(
            function () {
                if ($this->path() == '/') {
                    return true;
                };
            },
            $this->getTimeout()
        );
        $this->userSessionService->login('SiteManager');

        $this->assertNodeLocked($node_title);
        $this->administrativeNodeViewPage->go($nid);

        $buttons = $this->administrativeNodeViewPage->contextualToolbar->buttonInfo();
        unset($buttons['OnlineVersion']);
        unset($buttons['Offline']);
        unset($buttons['Unpublish']);
        // Remove the check for the Translation button if the Paddle i18n is not
        // enabled.
        if (!module_exists('paddle_i18n')) {
            unset($buttons['Translations']);
        }
        foreach (array_keys($buttons) as $button_name) {
            $this->administrativeNodeViewPage->contextualToolbar->{'button' . $button_name}->click();
            // The user should be allowed to click the "Preview Revision" button
            // even if the node is locked.
            if ($button_name == 'PreviewRevision') {
                // Get the path alias of the node by removing the "/" and "."
                // and lower-casing it.
                $alias = '/' . strtolower(str_replace(array('/', '.'), '', $node_title));
                // Replace spaces with dashes, as it's done on url aliases.
                $alias = str_replace(' ', '-', $alias);
                $language_prefix = MultilingualService::getLanguagePathPrefix($this);
                $expected_url = $this->base_url . ($language_prefix ? '/' . $language_prefix : '') . $alias;
                $this->assertEquals($expected_url, $this->url());
                $this->administrativeNodeViewPage->go($nid);
                continue;
            }

            // Check that we remain on the admin node view page.
            $this->administrativeNodeViewPage->checkArrival();

            // Verify the message that appears. Unfortunately it currently does not
            // have clean markup, so we just use a partial match.
            // @todo: When KANWEBS-2310 is in, this needs to be adjusted from
            // $this->messages->errorMessages() to
            // $this->messages->warningMessages()
            $warning_messages = $this->messages->warningMessages();
            if (empty($warning_messages)) {
                $warning_messages = $this->messages->errorMessages();
            }
            // Simplify the warning message to check, by replacing all non-word
            // characters with a space, and multiples spaces with a single space.
            $warning_text = preg_replace(array('/[^\w]/', '/\040+/'), ' ', $warning_messages[0]->text());
            $position_of_message =
                    strpos($warning_text, "This document is locked for editing by demo_chief_editor since");
            $this->assertTrue(false !== $position_of_message);
        }
    }

    /**
     * Tests if the workflow buttons act upon locked content properly for chief editors.
     *
     * @group contentType
     * @group workflowLockingTestBase
     * @group contentLocking
     * @group workflow
     */
    public function testWorkflowLocking()
    {
        $this->userSessionService->login('Editor');

        // Create a new page of the given type.
        $nid = $this->setupNode();
        $node = node_load($nid);
        $node_title = $node->title;

        // Check that other users do not have access to the node while it is
        // locked. Go to the node edit to lock it.
        $this->administrativeNodeViewPage->go($nid);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageProperties->click();

        // We can not log out, logging out will release all locks the logged out
        // user holds. Instead clear all browser cookies.
        $this->userSessionService->clearCookies();

        $this->url('/');
        $this->acceptAlert();
        $this->waitUntil(
            function () {
                if ($this->path() == '/') {
                    return true;
                };
            },
            $this->getTimeout()
        );
        $this->userSessionService->login('ChiefEditor');
        $this->administrativeNodeViewPage->go($nid);

        $buttons = $this->administrativeNodeViewPage->contextualToolbar->buttonInfo();
        unset($buttons['Offline']);
        unset($buttons['OnlineVersion']);
        unset($buttons['PreviewRevision']);
        unset($buttons['Unpublish']);
        // Remove the check for the Translation button if the Paddle i18n is not
        // enabled.
        if (!module_exists('paddle_i18n')) {
            unset($buttons['Translations']);
        }
        foreach (array_keys($buttons) as $button_name) {
            if (in_array($button_name, array('ToEditor', 'ToChiefEditor'))) {
                /** @var DropdownButton $button */
                $button = $this->administrativeNodeViewPage->contextualToolbar->{'dropdownButton' . $button_name};
                $button->getButton()->click();
                // Click on the actual link to send it.
                $button->getButtonInDropdown('Assign to any')->click();
            } else {
                $this->administrativeNodeViewPage->contextualToolbar->{'button' . $button_name}->click();
            }

            // Check that we remain on the admin node view page.
            $this->administrativeNodeViewPage->checkArrival();

            // Verify the message that appears. Unfortunately it currently does not
            // have clean markup, so we just use a partial match.
            // @todo: When KANWEBS-2310 is in, this needs to be adjusted from
            // $this->messages->errorMessages() to
            // $this->messages->warningMessages()
            $warning_messages = $this->messages->warningMessages();
            try {
                $error_messages = $this->messages->errorMessages();
            } catch (\Exception $e) {
                // Do nothing.
            }

            // Simplify the warning message to check, by replacing all non-word
            // characters with a space, and multiples spaces with a single space.
            $position_of_message = false;
            $warning_text = '';
            if (!empty($warning_messages[0])) {
                $warning_text = preg_replace(array('/[^\w]/', '/\040+/'), ' ', $warning_messages[0]->text());
                $message = "This document is locked for editing by demo_editor since";
                $position_of_message = strpos($warning_text, $message);
            }

            // @todo: When KANWEBS-2310 is in, this  "if" needs to be removed.
            if (!$position_of_message) {
                $warning_text = preg_replace(array('/[^\w]/', '/\040+/'), ' ', $error_messages[0]->text());
                $position_of_message = strpos($warning_text, "This document is locked for editing by demo_editor since");
            }
            $this->assertTrue(false !== $position_of_message);

            // Check that the break lock message is shown.
            $edit_message = strpos($warning_text, 'to check back in now');
            $this->assertTrue(false !== $edit_message);
        }

        // Now check that we can break the lock.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $element = $this->element($this->using('xpath')->value('//div[contains(@class, "error")]//a'));
        $element->click();

        $status_messages = $this->messages->statusMessages();
        $this->assertMessagesContainsMessage($status_messages, 'has been released.');

        // Check that we remain on the admin node view page.
        $this->administrativeNodeViewPage->checkArrival();

        $this->assertNodeNotLocked($node_title);
    }

    /**
     * Assert that a node is locked, in a new browser window.
     *
     * We can not always verify if a node is locked in the current window,
     * because navigating away from the page that initiated the lock also
     * releases the lock again.
     *
     * @param string $node_title
     *   The title of the node.
     */
    protected function assertNodeLockedInNewWindow($node_title)
    {
        $this->inNewWindow(
            function (NodeLockingTestBase $driver) use ($node_title) {
                $driver->assertNodeLocked($node_title);
            }
        );
    }

    /**
     * Assert that a node is not locked.
     *
     * Tries to find a lock for the node with a specific title in the table at
     * admin/content/content_lock.
     *
     * @param string $node_title The title of the node.
     */
    public function assertNodeNotLocked($node_title)
    {
        $this->contentLockPage->go();
        $lock_rows = $this->contentLockPage->findRows($node_title);
        $this->assertEquals(0, count($lock_rows));
    }

    /**
     * Assert that a node is locked.
     *
     * Tries to find a lock for the node with a specific title in the table at
     * admin/content/content_lock.
     *
     * @param string $node_title The title of the node.
     */
    public function assertNodeLocked($node_title)
    {
        $this->contentLockPage->go();
        $rows = $this->contentLockPage->findRows($node_title);
        $this->assertEquals(1, count($rows));
    }

    /**
     * Assert a given message is present in a list of messages.
     *
     * @param PHPUnit_Extensions_Selenium2TestCase_Element[] $this->messages
     *   A list of messages.
     * @param string $messageString
     *   The message to find.
     */
    public function assertMessagesContainsMessage(array $messages, $messageString)
    {
        $this->assertTrue($messages !== null);
        $found = false;
        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element $message */
        foreach ($messages as $message) {
            if (strpos($message->text(), $messageString) !== false) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found, 'Message "' . $messageString . '" found.');
    }

    /**
     * Assert a given message is not present in a list of messages.
     *
     * @param string $messageString
     *   The message to find.
     */
    public function assertMessagesNotContainsMessage($messageString)
    {
        $found = false;

        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element $message */
        foreach ($this->messages as $message) {
            if ($message->text() == $messageString) {
                $found = true;
                break;
            }
        }

        $this->assertFalse($found, 'Message "' . $messageString . '" not found.');
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
