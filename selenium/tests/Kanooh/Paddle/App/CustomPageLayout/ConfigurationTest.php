<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\CustomPageLayout\ConfigurationTest.
 */

namespace Kanooh\Paddle\App\CustomPageLayout;

use Kanooh\Paddle\Apps\CustomPageLayout;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCustomPageLayout\AddEditPage\CustomPageLayoutAddPage;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCustomPageLayout\AddEditPage\CustomPageLayoutEditPage;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCustomPageLayout\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs configuration tests on the Custom Page Layout paddlet.
 * @package Kanooh\Paddle\App\CustomPageLayout
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ConfigurationTest extends WebDriverTestCase
{
    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var CustomPageLayoutAddPage
     */
    protected $customPageLayoutAddPage;

    /**
     * @var CustomPageLayoutEditPage
     */
    protected $customPageLayoutEditPage;

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
        $this->alphanumericDataProvider = new AlphanumericTestDataProvider();
        $this->configurePage = new ConfigurePage($this);
        $this->customPageLayoutAddPage = new CustomPageLayoutAddPage($this);
        $this->customPageLayoutEditPage = new CustomPageLayoutEditPage($this);
        $this->userSessionService = new UserSessionService($this);

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new CustomPageLayout);

        $this->userSessionService->login('SiteManager');
    }

    public function testCustomPageLayoutCreation()
    {
        $this->configurePage->go();

        // Create a new custom page layout.
        $this->configurePage->contextualToolbar->buttonAdd->click();
        $this->customPageLayoutAddPage->checkArrival();

        // Fill in a title.
        $test_title = $this->alphanumericDataProvider->getValidValue();
        $this->customPageLayoutAddPage->form->title->fill($test_title);

        // Save the page and assert you get redirected to the config page.
        $this->customPageLayoutAddPage->form->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();

        // Assert that you can find the new layout in the list of layouts.
        $row = $this->configurePage->layoutsTable->getRowByName($test_title);

        // Assert that you can reach the layout edit page.
      /* @var \Kanooh\Paddle\Pages\Element\CustomPageLayout\CustomPageLayoutsTableRow $row */
        $row->linkEdit->click();
        $this->customPageLayoutEditPage->checkArrival();

        // Assert that you are on the right edit page (by asserting the title).
        $this->assertEquals($test_title, $this->customPageLayoutAddPage->form->title->getContent());

        // Click on back and assert you reach the config page again.
        $this->customPageLayoutEditPage->form->contextualToolbar->buttonBack->click();
        $this->configurePage->checkArrival();
    }
}
