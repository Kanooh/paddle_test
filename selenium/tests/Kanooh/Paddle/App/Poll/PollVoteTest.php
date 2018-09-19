<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Product\PollVoteTest.
 */

namespace Kanooh\Paddle\App\Poll;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\Poll;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\PollPanelsContentType;
use Kanooh\Paddle\Pages\Node\EditPage\Poll\PollPage;
use Kanooh\Paddle\Pages\Node\ViewPage\Poll\PollViewPage as FrontEndPollPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on the Paddle Product Paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PollVoteTest extends WebDriverTestCase
{
    /**
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
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
     * Layout page.
     *
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * @var PollPage
     */
    protected $editPage;

    /**
     * @var FrontEndPollPage
     */
    protected $frontendPage;

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
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->editPage = new PollPage($this);
        $this->frontendPage = new FrontEndPollPage($this);
        $this->layoutPage = new LayoutPage($this);

        // Go to the login page and log in as Chief Editor.
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Poll);
    }

    /**
     * Tests the poll voting for anonymous users on a poll page.
     *
     * @group poll
     * @group authcache
     */
    public function testPollVotingAnonymousUser()
    {
        $nid = $this->contentCreationService->createPollPageViaUI();
        $this->contentCreationService->moderateNode($nid, 'published');

        $this->assertAnonymousUserVoting($nid);
    }

    /**
     * Tests the poll voting for anonymous users on a poll pane.
     *
     * @group panes
     * @group poll
     * @group authcache
     */
    public function testPollPaneVotingAnonymousUser()
    {
        $poll_page_nid = $this->contentCreationService->createPollPageViaUI();
        $this->contentCreationService->moderateNode($poll_page_nid, 'published');

        $basic_page_nid = $this->contentCreationService->createBasicPage();
        // Add poll pane to basic page.
        $this->layoutPage->go($basic_page_nid);
        $region = $this->layoutPage->display->getRandomRegion();

        $content_type = new PollPanelsContentType($this);
        $webdriver = $this;
        $callable = new SerializableClosure(
            function () use ($content_type, $poll_page_nid, $webdriver) {
                if ($poll_page_nid) {
                    $content_type->getForm()->autocompleteField->fill('node/' . $poll_page_nid);
                    $autocomplete = new AutoComplete($webdriver);
                    $autocomplete->waitUntilDisplayed();
                    $autocomplete->waitUntilSuggestionCountEquals(1);
                    $autocomplete->pickSuggestionByPosition(0);
                }
            }
        );
        $region->addPane($content_type, $callable);

        // Save the page.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Publish the basic page so anonymous user can reach it.
        $this->contentCreationService->moderateNode($basic_page_nid, 'published');

        $this->assertAnonymousUserVoting($basic_page_nid);
    }

    public function assertAnonymousUserVoting($nid)
    {
        // Logout so we are anonymous and vote.
        $this->userSessionService->logout();
        $this->frontendPage->go($nid);
        $this->assertNotEmpty($this->frontendPage->pollView->votingForm);

        // Choose the first one and submit the vote.
        $this->frontendPage->pollView->votingForm->pollChoices[0]->select();
        $this->moveto($this->frontendPage->pollView->votingForm->votingButton);
        $this->frontendPage->pollView->votingForm->votingButton->click();
        $this->waitUntilTextIsPresent('Your vote was recorded.');

        // Verify the results are shown.
        $this->assertNotEmpty($this->frontendPage->pollView->results);

        // Refresh the page and verify your vote still stands.
        $this->frontendPage->go($nid);
        $this->assertNotEmpty($this->frontendPage->pollView->results);

        // Destroy the user session to simulate a new anonymous user.
        $user_id = $this->userSessionService->getCurrentUserId();
        drupal_session_destroy_uid($user_id);
        $this->userSessionService->clearCookies();

        // Reload the page and verify that we can vote again.
        $this->frontendPage->reloadPage();
        $this->assertNotEmpty($this->frontendPage->pollView->votingForm);

        // Choose the first one and submit the vote.
        $this->frontendPage->pollView->votingForm->pollChoices[0]->select();
        $this->moveto($this->frontendPage->pollView->votingForm->votingButton);
        $this->frontendPage->pollView->votingForm->votingButton->click();
        $this->waitUntilTextIsPresent('Your vote was recorded.');

        // Verify the results are shown.
        $this->assertNotEmpty($this->frontendPage->pollView->results);
    }
}
