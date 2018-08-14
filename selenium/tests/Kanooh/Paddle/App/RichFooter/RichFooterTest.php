<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\RichFooter\RichFooterTest.
 */

namespace Kanooh\Paddle\App\RichFooter;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\RichFooter;
use Kanooh\Paddle\Pages\Admin\Apps\AppsOverviewPage\AppsOverviewPage;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleRichFooter\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerAddPage\ThemerAddPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ThemerEditPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on the Paddle Rich Footer Paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class RichFooterTest extends WebDriverTestCase
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
     * @var AppsOverviewPage
     */
    protected $appsOverviewPage;

    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var array
     */
    protected $expected_layouts = array(
        //'\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col6to6Layout',
        //'\Kanooh\Paddle\Pages\Element\Layout\Paddle3ColLayout',
        '\Kanooh\Paddle\Pages\Element\Layout\Paddle4ColFullLayout',
    );

    /**
     * @var FrontPage
     */
    protected $frontPage;

    /**
     * @var ThemerAddPage
     */
    protected $themerAddPage;

    /**
     * @var ThemerEditPage
     */
    protected $themerEditPage;

    /**
     * @var ThemerOverviewPage
     */
    protected $themerOverviewPage;

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
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->appsOverviewPage = new AppsOverviewPage($this);
        $this->configurePage = new ConfigurePage($this);
        $this->frontPage = new FrontPage($this);
        $this->themerAddPage = new ThemerAddPage($this);
        $this->themerEditPage = new ThemerEditPage($this);
        $this->themerOverviewPage = new ThemerOverviewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->login('ChiefEditor');
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new RichFooter);
    }

    /**
     * Tests the rich footer configuration.
     */
    public function testRichFooterConfiguration()
    {
        $this->appsOverviewPage->go();
        $apps = $this->appsOverviewPage->apps;
        /** @var AppElement */
        $apps['paddle_rich_footer']->configureButton->click();
        $this->configurePage->checkArrival();

        $region = $this->configurePage->display->getRandomRegion();

        $content_type = new CustomContentPanelsContentType($this);
        $word = $this->alphanumericTestDataProvider->getValidValue(16);
        $callable = new SerializableClosure(function () use ($content_type, $word) {
            $content_type->getForm()->body->setBodyText($word);
        });

        $region->addPane($content_type, $callable);
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->assertTextPresent('has been updated.');
        $this->assertTextPresent($word);

        // Create new theme.
        $this->userSessionService->switchUser('SiteManager');
        $this->themerOverviewPage->go();

        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();

        // Create a new theme and just save it.
        $human_theme_name = $this->alphanumericTestDataProvider->getValidValue();
        $this->themerAddPage->name->clear();
        $this->themerAddPage->name->value($human_theme_name);
        $this->themerAddPage->baseTheme->selectOptionByValue('vo_standard');
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();
        $theme_name = $this->themerEditPage->getThemeName();
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();

        // Enable the theme.
        $this->themerOverviewPage->theme($theme_name)->enable->click();
        $this->themerOverviewPage->checkArrival();

        $this->themerOverviewPage->getActiveTheme()->edit->click();
        // Set the "Footer style" value.
        $this->themerEditPage->footer->header->click();
        $this->themerEditPage->footer->footerStyleRadioButtons->richFooter->select();
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();

        $this->frontPage->go();
        $this->assertTextPresent($word);
    }
}
