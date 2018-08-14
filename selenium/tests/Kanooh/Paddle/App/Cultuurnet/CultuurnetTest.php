<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Cultuurnet\CultuurnetTest.
 */

namespace Kanooh\Paddle\App\Cultuurnet;

use Kanooh\Paddle\Apps\Cultuurnet;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class CultuurnetTest extends WebDriverTestCase
{

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * Admin node view page.
     *
     * @var FrontPage
     */
    protected $frontPage;

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
        $this->frontPage = new FrontPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);
    }

    /**
     * Ensures the Cultuurnet app does not block search engines by default.
     */
    public function testMetaRobotsNoIndex()
    {
        $this->appService->enableApp(new Cultuurnet);
        $this->frontPage->go();
        try {
            $meta_element = $this->byXPath("//meta[@name='robots']");
        } catch (\Exception $e) {
            // No robots metatag is also fine.
            return;
        }
        $this->assertNotContains('noindex', $meta_element->attribute('content'));
    }
}
