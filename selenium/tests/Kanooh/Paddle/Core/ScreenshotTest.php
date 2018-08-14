<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ScreenshotTest.
 */

namespace Kanooh\Paddle\Core;

use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ScreenshotTest extends WebDriverTestCase
{

    /**
     * Tests the modal to add a pane on a landing page.
     *
     * @todo This is early experimental code. Refactor to use PaddlePage
     *   classes. Do not use this as a base for new tests.
     *
     * @group experimental
     */
    public function testCreateAndEditLandingPage()
    {
        $username = 'demo_chief_editor';
        $password = 'demo';

        $this->url('/user');

        $this->storeScreenshot('before_login');

        $this->byName('name')->value($username);
        $this->byName('pass')->value($password);
        $this->byCss('input.form-submit')->click();

        $loggedin = $this->byClassName('logged-in')->size();

        $this->assertTrue($loggedin > 0, 'User is logged in');

        $this->storeScreenshot('after_login');
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
}
