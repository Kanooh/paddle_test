<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Regression\AdditionalFieldsTest.
 */

namespace Kanooh\Paddle\Core\Regression;

use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests that the fieldset title of the additional fields is present only when
 * there are field.
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 *
 * @see https://one-agency.atlassian.net/browse/KANWEBS-1876
 */
class AdditionalFieldsTest extends WebDriverTestCase
{

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
        $this->editPage = new EditPage($this);

        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->login('ChiefEditor');
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
     * Test that the unnecessary fieldset title of the additional fields is
     * present only when there are field.
     *
     * @group contentType
     * @group additionalFieldsTest
     */
    public function testAdditionalFieldsTitleCorrectlyDisplayed()
    {
        $title_selector = '.pane-additional-fields .contact-information h2';
        // Create a landing page and go to its edit page.
        $content_service = new ContentCreationService($this, $this->userSessionService);
        $nid = $content_service->createLandingPage();
        $this->editPage->go(array($nid));

        // Check that the additional fields title is not there.
        $elements = $this->elements(
            $this->using('css selector')->value($title_selector)
        );
        $this->assertEquals(0, count($elements));

        // Verify that removing the fieldset title doesn't affect the
        // Organizational unit nodes.
        $nid = $content_service->createOrganizationalUnit();
        $this->editPage->go(array($nid));
        $this->byCssSelector($title_selector);
    }
}
