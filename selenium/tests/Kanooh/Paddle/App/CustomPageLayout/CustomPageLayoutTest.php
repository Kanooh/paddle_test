<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\CustomPageLayout\CustomPageLayoutTest.
 */

namespace Kanooh\Paddle\App\CustomPageLayout;

use Kanooh\Paddle\Apps\CustomPageLayout;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCustomPageLayout\AddEditPage\CustomPageLayoutAddPage;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCustomPageLayout\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Element\Modal\ChangeLayoutModal;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on the Paddle Google Custom Page Layout Paddlet.
 *
 * @package Kanooh\Paddle\App\CustomPageLayout
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class CustomPageLayoutTest extends WebDriverTestCase
{

    /**
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

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
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var CustomPageLayoutAddPage
     */
    protected $customPageLayoutAddPage;

    /**
     * @var PanelsContentPage
     */
    protected $panelsContentPage;

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
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericDataProvider = new AlphanumericTestDataProvider();
        $this->configurePage = new ConfigurePage($this);
        $this->customPageLayoutAddPage = new CustomPageLayoutAddPage($this);
        $this->panelsContentPage = new PanelsContentPage($this);
        $this->userSessionService = new UserSessionService($this);

        // Services relying on the userSessionService.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Enable the app if it is not yet enabled.
        $this->appService->enableApp(new CustomPageLayout);

        $this->userSessionService->login('SiteManager');
    }

    /**
     * Tests if newly made layouts are shown if older nodes want to change
     * layout.
     */
    public function testLayoutsShownInChangeLayoutList()
    {
        // Create a landing page with a random layout.
        $nid = $this->contentCreationService->createLandingPage();

        // Create a custom layout.
        $this->configurePage->go();

        $this->configurePage->contextualToolbar->buttonAdd->click();
        $this->customPageLayoutAddPage->checkArrival();

        $title = $this->alphanumericDataProvider->getValidValue();
        $this->customPageLayoutAddPage->form->title->fill($title);

        $this->customPageLayoutAddPage->form->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();

        // Go to the Panels Content page of the landing page.
        $this->panelsContentPage->go($nid);

        // Change the layout.
        $this->panelsContentPage->contextualToolbar->buttonChangeLayout->click();

        $modal = new ChangeLayoutModal($this);
        $modal->waitUntilOpened();

        // Select the responsive layouts category.
        $modal->selectLayoutCategory->selectOptionByValue('Responsive');
        $change_layout_xpath = '//div[contains(@class, "layout-link")]/div/a[@data-layout-name = "responsive:' . strtolower($title) . '"]';
        // Assert that you can find the layout in the list.
        $this->waitUntilElementIsDisplayed($change_layout_xpath);
        $layout = $this->element($this->using('xpath')->value($change_layout_xpath));
        $layout->click();

        $this->waitUntilElementIsPresent('//form[contains(@id, "panels-change-layout")]');
        $modal->confirmButton->click();
        $modal->waitUntilClosed();
        $this->panelsContentPage->checkArrival();

        // Save the page.
        $this->panelsContentPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
    }
}
