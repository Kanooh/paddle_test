<?php

/**
 * @file
 * Contains \Kanooh\Paddle\ModerationTest.
 */

namespace Kanooh\Paddle\Core\ContentType\LandingPage;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\LandingPageViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Element\Modal\EditPaneModal;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Pages\Element\PreviewToolbar\PreviewToolbar;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\PaddlePage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests for content moderation workflows of landing pages.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ModerationTest extends WebDriverTestCase
{

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * The 'Add' page of the Paddle Content Manager module.
     *
     * @var AddPage $add_content_page
     */
    protected $add_content_page;

    /**
     * The administrative node view of a landing page.
     *
     * @var LandingPageViewPage $administrative_node_view_page
     */
    protected $administrative_node_view_page;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The page that allows to edit the Panels display of a node.
     *
     * @var PanelsContentPage $panels_content_page
     */
    protected $panels_content_page;

    /**
     * The node edit page.
     *
     * @var EditPage $page_information_page
     */
    protected $page_information_page;

    /**
     * The random data generation class.
     *
     * @var Random $random
     */
    protected $random;

    /**
     * The title of the test node created in this test.
     *
     * @var string $node_title
     */
    protected $node_title;

    /**
     * The body of the test node created in this test.
     *
     * @var string $node_title
     */
    protected $node_body;

    /**
     * The content of the custom content pane added to the test node.
     *
     * @var string $pane_custom_content
     */
    protected $pane_custom_content;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Instantiate the Pages that will be visited in the test.
        $this->userSessionService = new UserSessionService($this);
        $this->add_content_page = new AddPage($this);
        $this->administrative_node_view_page = new LandingPageViewPage($this);
        $this->panels_content_page = new PanelsContentPage($this);
        $this->page_information_page = new EditPage($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        $this->random = new Random();

        $this->node_title = array();
        $this->node_body = array();
        $this->pane_custom_content = array();

        foreach (array('pane_custom_content', 'node_title', 'node_body') as $node_content) {
            foreach (array('published', 'unpublished', 'editorial', 'chief_editorial') as $content_type) {
                $this->{$node_content}[$content_type] = $this->random->name(8);
            }
        }

        // Working with landing pages can be slow and this often times out on
        // Saucelabs. Increase the timeout until we get the necessary time
        // allocated to improve the performance of landing pages in earnest.
        $this->setTimeout(45000);

        // Go to the login page and log in as editor.
        $this->userSessionService->login('Editor');
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

    /**
     * Tests moderation of landing pages.
     *
     * @group modals
     * @group panes
     * @group workflow
     */
    public function testLandingPageModeration()
    {
        // Create new Landing page.
        $this->add_content_page->go();

        $current_layout = 'paddle_celebi';
        // Choose a second (different) layout.
        do {
            $second_layout = 'paddle_2_col_9_3_a';
        } while ($second_layout == $current_layout);
        $nid = $this->contentCreationService->createLandingPage($current_layout);

        // After creating the page we are redirected to the administrative node
        // view.
        // Verify that the appropriate buttons are present in the contextual
        // toolbar.
        $this->assertContextualToolbarInStateDraft($this->administrative_node_view_page);

        // @todo - Verify that the you cannot find "Add pane" button.
        $ipe_display_xpath = '//div[contains(@class, "panels-ipe-display-container")]';

        // Add a pane. Later we will verify with this pane that the panes are
        // part of the workflow.
        $this->administrative_node_view_page->contextualToolbar->buttonPageLayout->click();
        $this->panels_content_page->checkArrival();

        $region = $this->panels_content_page->display->getRandomRegion();
        $custom_content_pane = new CustomContentPanelsContentType($this);
        $custom_content_pane->body = $this->pane_custom_content['unpublished'];
        $pane = $region->addPane($custom_content_pane);

        $this->panels_content_page->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The page has been updated.');

        // Check if we're being redirected correctly.
        $this->administrative_node_view_page->checkArrival();

        // Assert the node edit page and add some content.
        $this->assertPageProperties('unpublished');

        // Click "Preview" to verify the correct (unpublished) node revision is
        // shown.
        $to_check = array('pane_custom_content', 'node_title', 'node_body');
        $this->assertPageContentFrontEnd('PreviewRevision', 'unpublished', $to_check);

        // Send the page to any chief editor.
        $this->sentToState('needs_review');

        // Login as chief editor.
        $this->userSessionService->switchUser('ChiefEditor');

        // Go to the page created by the editor.
        $this->administrative_node_view_page->go(array($nid));

        // Verify that we see what the editor provided us with.
        $this->assertPageContentFrontEnd('PreviewRevision', 'unpublished', $to_check);

        // Verify that the appropriate buttons are present in the contextual
        // toolbar for chief editor.
        $buttons = array('PageLayout', 'ToEditor', 'PageProperties', 'PreviewRevision', 'Publish');
        $this->administrative_node_view_page->contextualToolbar->checkButtons($buttons);

        // Now change the node pane created by the editor with the content to be
        // published.
        $this->administrative_node_view_page->contextualToolbar->buttonPageLayout->click();
        $this->panels_content_page->checkArrival();

        $pane->toolbar->buttonEdit->click();
        $modal = new EditPaneModal($this);
        // Fill in the configuration form.
        $custom_content_pane->body = $this->pane_custom_content['published'];
        $modal->waitUntilOpened();
        $custom_content_pane->fillInConfigurationForm($modal);
        // Submit the configuration form.
        $modal->submit();
        $modal->waitUntilClosed();

        $this->clickElement('//a[@data-paddle-contextual-toolbar-click="panels-ipe-save"]');
        $this->waitUntilTextIsPresent('The page has been updated.');

        // Check if we're being redirected correctly.
        $this->administrative_node_view_page->checkArrival();

        // Publish the landing page and verify the contextual toolbar buttons
        // changed.
        $this->administrative_node_view_page->contextualToolbar->buttonPublish->click();
        $this->assertContextualToolbarInStatePublished($this->administrative_node_view_page);
        // Click "Preview" to verify the correct (published) pane content is
        // shown.
        $this->assertPageContentFrontEnd('PreviewRevision', 'published', array('pane_custom_content'));
        // Check that the old ("unpublished") node title and body are still
        // there.
        $this->assertPageContentFrontEnd('PreviewRevision', 'unpublished', array('node_title', 'node_body'));

        // Change the node title and body to create a draft.
        $this->assertPageProperties('published');
        // Verify it went into "unpublished" state.
        $this->assertContextualToolbarInStateDraft($this->administrative_node_view_page);

        // Check the 2 versions - the published one and the draft one we just
        // created.
        $this->assertPageContentFrontEnd('OnlineVersion', 'published', array('pane_custom_content'));
        $this->assertPageContentFrontEnd('OnlineVersion', 'unpublished', array('node_title', 'node_body'));
        $this->assertPageContentFrontEnd('PreviewRevision', 'published', array('node_title', 'node_body'));

        // Publish again.
        $this->administrative_node_view_page->contextualToolbar->buttonPublish->click();
        $this->assertPageContentFrontEnd('PreviewRevision', 'published', $to_check);

        // Go to the Panels content page.
        $this->administrative_node_view_page->contextualToolbar->buttonPageLayout->click();
        $this->panels_content_page->checkArrival();

        // Change the layout.
        $this->panels_content_page->changeLayout($second_layout);

        $this->panels_content_page->contextualToolbar->buttonSave->click();
        $this->administrative_node_view_page->waitUntilPageIsLoaded();
        $this->waitUntilTextIsPresent('The page has been updated.');

        $this->assertContextualToolbarInStateDraft($this->administrative_node_view_page);
        $this->administrative_node_view_page->contextualToolbar->buttonPublish->click();

        // Unpublish it to verify the correct buttons are shown.
        $this->administrative_node_view_page->contextualToolbar->buttonOffline->click();
        $this->assertContextualToolbarInStateDraft($this->administrative_node_view_page);

        // Change the state to "Online".
        $this->administrative_node_view_page->contextualToolbar->buttonPublish->click();

        // Login as editor again.
        $this->userSessionService->switchUser('Editor');

        // We should see no buttons.
        $this->assertFalse($this->isTextPresent('Page properties'));
    }

    /**
     * Asserts the buttons on pane edit page.
     *
     * @param PaddlePage $page
     *    The page on which to assert the buttons.
     */
    public function assertContextualToolbarInPaneEditState(PaddlePage $page)
    {
        $contextual_buttons = array('Back', 'Save', 'ChangeLayout');
        $page->contextualToolbar->checkButtons($contextual_buttons);
    }

    /**
     * Asserts the buttons in state "Draft".
     *
     * @param PaddlePage $page
     *    The page on which to assert the buttons.
     */
    public function assertContextualToolbarInStateDraft(PaddlePage $page)
    {
        $buttons = array('ToEditor', 'ToChiefEditor', 'PreviewRevision');
        if ($page instanceof LandingPageViewPage) {
            $buttons[] = 'PageLayout';
            $buttons[] = 'PageProperties';
        }

        if ($this->userSessionService->getCurrentUser() != 'Editor') {
            $buttons[] = 'Publish';
        }
        $page->contextualToolbar->checkButtons($buttons);
    }

    /**
     * Asserts the buttons in state "To editor".
     *
     * @param PaddlePage $page
     *    The page on which to assert the buttons.
     */
    public function assertContextualToolbarInStateToEditor(PaddlePage $page)
    {
        $buttons = array('PageProperties', 'PageLayout', 'ToChiefEditor', 'PreviewRevision');
        $page->contextualToolbar->checkButtons($buttons);
    }

    /**
     * Asserts the buttons in state "Published".
     *
     * @param PaddlePage $page
     *    The page on which to assert the buttons.
     */
    public function assertContextualToolbarInStatePublished(PaddlePage $page)
    {
        $buttons = array('PageProperties', 'PageLayout', 'PreviewRevision');
        if ($this->userSessionService->getCurrentUser() != 'Editor') {
            $buttons[] = 'Offline';
        }
        $page->contextualToolbar->checkButtons($buttons);
    }

    /**
     * Verifies that the "Page properties" button leads to the right page and
     * not to a modal dialog.
     *
     * @todo This is an assertion, so it should not perform any actions.
     *
     * @param string $node_content_key
     *   The type of node content - key of $this->node_title and
     *   $this->node_body.
     */
    public function assertPageProperties($node_content_key = 'unpublished')
    {
        $this->administrative_node_view_page->contextualToolbar->buttonPageProperties->click();
        $this->page_information_page->checkArrival();

        // Set some values to test with.
        $edit = array(
            'title' => $this->node_title[$node_content_key],
            'body[und][0][value]' => $this->node_body[$node_content_key],
        );
        $this->page_information_page->populateFields($edit);

        // Go back to the overview page and verify it.
        $this->page_information_page->contextualToolbar->buttonSave->click();
        $this->administrative_node_view_page->checkPath();
    }

    /**
     * Verifies that specific node content is shown on the front-end and the
     * rest are not.
     *
     * @param string $button
     *   The name of the button to click.
     * @param string $content_key
     *   The type of node content - key of $this->pane_custom_content.
     * @param array $to_check
     *   The type of node content to check.
     */
    public function assertPageContentFrontEnd($button, $content_key, $to_check)
    {
        $button_name = 'button' . $button;
        $this->administrative_node_view_page->contextualToolbar->$button_name->click();

        // Tests the presence of the different node content.
        foreach ($to_check as $node_content) {
            $this->waitUntilTextIsPresent($this->{$node_content}[$content_key]);
            foreach ($this->{$node_content} as $key => $content) {
                if ($key != $content_key) {
                    $this->assertFalse($this->isTextPresent($content));
                }
            }
        }

        // Return to the admin view.
        $previewToolbar = new PreviewToolbar($this);
        $previewToolbar->closeButton()->click();
    }

    /**
     * Sends a node to another state - handles "To editor" and "To chief editor"
     * dropdowns.
     *
     * @param string $state
     *   The state to change to - 'published' or 'offline'.
     */
    public function sentToState($state = 'to_check')
    {
        // Click on the header of the dropdown.
        $header_xpath = '//li[contains(@class, "moderate-to-' . str_replace('_', '-', $state) .
            '")]/a[contains(@class, "contextual-dropdown")]';
        $this->clickElement($header_xpath);

        // Click on the actual link to send it.
        $link_xpath = '//ul[@id="assignee-items-list-' . $state . '"]/li[contains(@class, "assignee_any")]/a';
        $this->clickElement($link_xpath);
    }

    /**
     * Clicks an element based on it's xpath.
     * @param $xpath string a xpath query string
     */
    public function clickElement($xpath)
    {
        $this->waitUntilElementIsDisplayed($xpath);
        $element = $this->element($this->using('xpath')->value($xpath));
        if ($element->displayed()) {
            $element->click();
        }
    }
}
