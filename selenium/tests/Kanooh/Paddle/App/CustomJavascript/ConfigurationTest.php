<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\CustomJavascript\ConfigurationTest.
 */

namespace Kanooh\Paddle\App\CustomJavascript;

use Kanooh\Paddle\Apps\CustomCSS;
use Kanooh\Paddle\Apps\CustomJavascript;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCustomJavascript\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerAddPage\ThemerAddPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ThemerEditPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\HttpRequest\HttpRequest;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs configuration tests on the Custom Javascript paddlet.
 * @package Kanooh\Paddle\App\CustomJavascript
 *
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
     * @var FrontPage
     */
    protected $frontPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericDataProvider;


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
        $this->configurePage = new ConfigurePage($this);
        $this->frontPage = new FrontPage($this);
        $this->alphanumericDataProvider = new AlphanumericTestDataProvider();
        $this->userSessionService = new UserSessionService($this);

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new CustomJavascript);

        $this->userSessionService->login('SiteManager');
    }

    /**
     * Tests the configuring of the Custom Javascript.
     */
    public function testCustomJavascriptConfiguration()
    {
        $this->configurePage->go();

        // Verify you can save a script.
        $script = '<script>Do nothing.</script><meta name="author" content="Willy Stinkt">';
        $this->configurePage->form->textArea->fill($script);
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');
        $this->assertEquals($script, $this->configurePage->form->textArea->getContent());

        $this->frontPage->go();
        $this->assertCount(1, $this->elements($this->using('xpath')->value('//head//script[text()="Do nothing."]')));
        $this->assertCount(1, $this->elements($this->using('xpath')->value('//head//meta[@name="author" and @content="Willy Stinkt"]')));

        $this->configurePage->go();
        $this->configurePage->form->textArea->clear();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');
        $this->frontPage->go();

        $this->assertCount(0, $this->elements($this->using('xpath')->value('//head//script[text()="Do nothing"]')));
        $this->assertCount(0, $this->elements($this->using('xpath')->value('//head//meta[@name="author" and @content="Willy Stinkt"]')));

        $this->userSessionService->switchUser('ChiefEditor');
        $this->configurePage->go();
        $this->assertTextPresent('You have insufficient access to manage custom JavaScript.');
    }
}
