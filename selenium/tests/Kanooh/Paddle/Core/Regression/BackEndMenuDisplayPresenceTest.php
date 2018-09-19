<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Regression\BackEndMenuDisplayPresence.
 */

namespace Kanooh\Paddle\Core\Regression;

use Kanooh\Paddle\Apps\Embed;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleEmbed\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests that the back-end menu is present on the node pages and on paddlet
 * config pages.
 *
 * @see https://one-agency.atlassian.net/browse/KANWEBS-2716
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class BackEndMenuDisplayPresenceTest extends WebDriverTestCase
{
    /**
     * The admin node view.
     *
     * @var ViewPage
     */
    protected $adminNodeViewPage;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * The paddlet configuration page.
     *
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

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

        // Instantiate the Pages that will be visited in the test.
        $this->adminNodeViewPage = new ViewPage($this);
        $this->configurePage = new ConfigurePage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        $this->userSessionService->login('ChiefEditor');

        // Enable an app that has config page if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Embed);
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
     * Tests that the back-end menu is present on the admin node view page and
     *  on paddlet config page.
     *
     * @group menu
     */
    public function testBackEndMenuDisplayPresence()
    {
        // Create a node. We should be on the admin node view page. Check for
        // the presence of the back-end menu on that page.
        $nid = $this->contentCreationService->createBasicPage();
        $this->adminNodeViewPage->go($nid);
        $this->adminNodeViewPage->adminMenuLinks->checkLinks(array('Dashboard', 'Structure', 'Content', 'PaddleStore'));

        // Go to Paddle Embed config page and check for the presence of the
        // back-end menu display there as well.
        $this->configurePage->go();
        $this->configurePage->adminMenuLinks->checkLinks(array('Dashboard', 'Structure', 'Content', 'PaddleStore'));
    }
}
