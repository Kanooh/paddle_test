<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Rate\RateTest.
 */

namespace Kanooh\Paddle\App\Rate;

use Kanooh\Paddle\Apps\Rate;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleRate\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on the Paddle Rate Paddlet.
 *
 * @package Kanooh\Paddle\App\Rate
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class RateTest extends WebDriverTestCase
{
    /**
     * @var ViewPage
     */
    protected $adminViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

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
     * @var FrontEndViewPage
     */
    protected $frontEndViewPage;

    /**
     * @var EditPage
     */
    protected $nodeEditPage;

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
        $this->adminViewPage = new ViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->configurePage = new ConfigurePage($this);
        $this->frontEndViewPage = new FrontEndViewPage($this);
        $this->nodeEditPage = new EditPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as a chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Rate);

        // Go to the configure page and enable the following types.
        $this->configurePage->go();
        $this->configurePage->configureForm->typeBasicPage->check();
        $this->configurePage->configureForm->typeLandingPage->check();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');
    }

    /**
     * Tests if the average score of the page rating has been produced
     * after having more than one person voted.
     *
     * @group Rate
     * @group authcache
     */
    public function testAverageRating()
    {
        // Create a node.
        $nid = $this->contentCreationService->createBasicPage();

        // Enable Rating for this node.
        $this->contentCreationService->enableRating($nid);

        // Click on the first star to vote as the first user.
        $this->frontEndViewPage->go($nid);
        $this->voteFivestar(1);

        // Log in as a different user.
        $this->userSessionService->switchUser('SiteManager');

        // Click on the fifth star to vote as the first user.
        $this->frontEndViewPage->go($nid);
        $this->voteFivestar(5);
        $this->refresh();

        $this->assertFivestarCount(3);
    }

    /**
     * Tests if anonymous users can register one vote per session.
     *
     * @group Rate
     * @group authcache
     */
    public function testAnonymousVote()
    {
        // Create a node and publish it.
        $nid = $this->contentCreationService->createLandingPage();
        $this->contentCreationService->enableRating($nid);

        $this->adminViewPage->go($nid);
        $this->adminViewPage->contextualToolbar->buttonPublish->click();
        $this->adminViewPage->checkArrival();

        // Log out to become anonymous.
        $this->userSessionService->logout();

        // Click on the first star to vote as the first user.
        $this->frontEndViewPage->go($nid);
        $this->voteFivestar(1);

        // Verify the result is shown.
        $this->assertFivestarCount(1);

        // The count has to stay visible even when visiting the page again
        // while page caching is enabled for anonymous users.
        $this->frontEndViewPage->reloadPage();
        $this->assertFivestarCount(1);

        // Remove the cookies to simulate a new anonymous user.
        $this->userSessionService->clearCookies();

        // Reload the page and verify that we can vote again.
        $this->frontEndViewPage->reloadPage();
        $this->assertFivestarCount(1);

        // Choose the first one and submit the vote.
        $this->voteFivestar(5);
        // Reload the page and verify that we can vote again.
        $this->assertFivestarCount(5);

        // Ensure the average of the 2 votes is shown when we visit the page again.
        $this->frontEndViewPage->reloadPage();
        $this->assertFivestarCount(3);
    }

    /**
     * Ensure a certain number of expected stars.
     *
     * @param int $count Number of expected stars.
     */
    public function assertFivestarCount($count)
    {
        // Check if the vote has been saved and there are three stars
        // highlighted.
        $xpath_highlighted_stars = '//div[contains(@class, "fivestar-widget")]/div[contains(@class, "on")]';
        $highlighted_stars = $this->elements($this->using('xpath')
            ->value($xpath_highlighted_stars));
        $this->assertCount($count, $highlighted_stars);
    }

    /**
     * Vote on a certain number of stars.
     *
     * @param int $number Number of stars to vote on.
     */
    public function voteFivestar($number)
    {
        if (in_array($number, array(1, 2, 3, 4, 5))) {
            $this->byCssSelector(".star-" . $number . " a")->click();
            // All clicks on stars are being followed by a sleep(1)
            // command because the next Selenium command is interfering with
            // the Ajax call from the stars.
            // @todo Use AjaxService to wait for Ajax call to complete
            // instead of sleeping 1 second.
            // @see KANWEBS-5105
            sleep(1);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Ensure we'll be able to change paddlet configuration.
        $this->userSessionService->switchUser('SiteManager');
        // Go to the configure page and disable the enabled types
        $this->configurePage->go();
        $this->configurePage->configureForm->typeBasicPage->uncheck();
        $this->configurePage->configureForm->typeLandingPage->uncheck();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');

        parent::tearDown();
    }
}
