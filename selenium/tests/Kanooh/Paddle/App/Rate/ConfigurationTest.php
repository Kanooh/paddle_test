<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Rate\ConfigurationTest.
 */

namespace Kanooh\Paddle\App\Rate;

use Kanooh\Paddle\Apps\Rate;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleRate\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndViewPage;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\WebDriver\WebDriverTestCase;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;

/**
 * Performs configuration tests on the Rate paddlet.
 *
 * @package Kanooh\Paddle\App\Rate
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ConfigurationTest extends WebDriverTestCase
{
    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var ViewPage
     */
    protected $adminViewPage;

    /**
     * @var FrontEndViewPage
     */
    protected $frontEndViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var EditPage
     */
    protected $nodeEditPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Prepare some variables for later use.
        $this->adminViewPage = new ViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->frontEndViewPage = new FrontEndViewPage($this);
        $this->configurePage = new ConfigurePage($this);
        $this->nodeEditPage = new EditPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as a site manager.
        $this->userSessionService->login('SiteManager');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Rate);
    }

    /**
     * Tests if that when you enable/disable the rate checkbox on the
     * configuration page that the stars appear/disappear
     *
     * @group Rate
     */
    public function testEnableDisableConfiguration()
    {
        // Create a node.
        $nid = $this->contentCreationService->createBasicPage();

        // Go to the configuration page and enable the checkbox for this content
        // type.
        $this->configurePage->go();
        $this->assertTextPresent('Basic page');

        $this->configurePage->configureForm->typeBasicPage->check();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');

        // Verify that the checkbox is visible and unselected.
        $this->nodeEditPage->go($nid);
        $this->assertTrue($this->nodeEditPage->enableRatingCheckbox->isDisplayed());
        $this->assertFalse($this->nodeEditPage->enableRatingCheckbox->isChecked());

        //Enable rating for this node.
        $this->contentCreationService->enableRating($nid);

        // Go to the front-end page of the node.
        $this->frontEndViewPage->go($nid);

        // Verify that the stars are present.
        $xpath = '//div[contains(@class, "field-type-fivestar")]';
        $this->waitUntilElementIsPresent($xpath);

        // Go to the configuration page and disable the checkbox for this
        // content type.
        $this->configurePage->go();
        $this->assertTextPresent('Basic page');

        $this->configurePage->configureForm->typeBasicPage->uncheck();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');

        // Go to the front-end page of the node.
        $this->frontEndViewPage->go($nid);

        // Verify that the stars are not present anymore.
        $this->waitUntilElementIsNoLongerPresent($xpath);

        // Go to the Edit page of the node.
        $this->nodeEditPage->go($nid);

        // Verify that the checkbox is not present anymore.
        $xpath_checkbox = '//input[contains(@name, "field_paddle_enable_rating[und]")]';
        $this->waitUntilElementIsNoLongerPresent($xpath_checkbox);
    }
}
