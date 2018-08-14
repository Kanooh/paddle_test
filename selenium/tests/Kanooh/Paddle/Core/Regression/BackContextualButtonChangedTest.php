<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Regression\BackContextualButtonChangedTest.
 */

namespace Kanooh\Paddle\Core\Regression;

use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests that the "Back" in the contextual toolbar on most pages in the back-end
 * is not replaced by the "Create new theme" button.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 *
 * @see https://one-agency.atlassian.net/browse/KANWEBS-4190
 */
class BackContextualButtonChangedTest extends WebDriverTestCase
{
    /**
     * The administrative node view.
     *
     * @var ViewPage
     */
    protected $administrativeNodeView;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The node edit page.
     *
     * @var EditPage
     */
    protected $editPage;

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
        $this->administrativeNodeView = new ViewPage($this);
        $this->editPage = new EditPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->login('ChiefEditor');
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
    }

    /**
     * Tests that the "Back" in the contextual toolbar on most pages in the back-end
     * is not replaced by the "Create new theme" button.
     *
     * @group regression
     */
    public function testBackContextualButtonChanged()
    {
        $nid = $this->contentCreationService->createBasicPage();
        $this->editPage->go($nid);
        $this->editPage->contextualToolbar->buttonBack->click();
        $this->administrativeNodeView->checkArrival();
    }
}
