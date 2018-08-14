<?php
/**
* @file
* Contains \Kanooh\Paddle\App\Multilingual\PaneMultilingualTestBase.
*/

namespace Kanooh\Paddle\App\Multilingual\Pane;

use Kanooh\Paddle\Apps\Multilingual;
use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\LandingPageViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage as LandingPagePanelsContentPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\MultilingualService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
* Class PaneMultilingualTestBase
* @package Kanooh\Paddle\App\Multilingual\Pane
*
* @runTestsInSeparateProcesses
* @preserveGlobalState disabled
*/
abstract class PaneMultilingualTestBase extends WebDriverTestCase
{

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var CleanUpService
     */
    protected $cleanUpService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var SearchPage
     */
    protected $contentManagerPage;

    /**
     * @var LandingPageViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var LandingPagePanelsContentPage
     */
    protected $landingPagePanelsPage;

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
        $this->administrativeNodeViewPage = new LandingPageViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->cleanUpService = new CleanUpService($this);
        $this->contentManagerPage = new SearchPage($this);
        $this->landingPagePanelsPage = new LandingPagePanelsContentPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Go to the login page and log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not enabled yet.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Multilingual);

        // Make sure the tests have the expected multilingual configuration.
        MultilingualService::setPaddleTestDefaults($this);
    }

    /**
     * Tests the multilingual functionality for panes.
     */
    abstract public function testMultilingual();

    /**
     * Asserts if the autocomplete suggestions contains the title.
     *
     * @param string $title
     *   The title of the node.
     * @param array $suggestions
     *   The suggestions for the autocomplete.
     * @param bool|false $present
     *   Whether the title should be present or not.
     */
    protected function assertSuggestionPresent($title, $suggestions, $present = false)
    {
        $found = false;
        // Loop over the suggestions and check if 1 of the values contains the title.
        foreach ($suggestions as $suggestion) {
            // The suggestion contains "$title . node/NID". That is why we do the strpos.
            if (strpos($suggestion, $title) !== false) {
                $found = true;
            }
        }

        // Assert the correct assumption.
        $this->assertEquals($present, $found);
    }
}
