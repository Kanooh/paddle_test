<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Core\Themer\VerticalMenuStyleTest.
 */

namespace Kanooh\Paddle\Core\Themer;

use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage\MenuOverviewPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerAddPage\ThemerAddPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ThemerEditPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\CreateMenuItemModal;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;
use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Utilities\AjaxService;
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;

/**
 * Test all themer menu styles.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 *
 */
class VerticalMenuStyleTest extends WebDriverTestCase
{

    /**
     * Test data provider for alphanumeric data.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericDataProvider;
    /**
     * The administrative node view.
     *
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * The homepage.
     *
     * @var FrontPage
     */
    protected $frontPage;

    /**
     * The 'Add' page of the Paddle Themer module.
     *
     * @var ThemerAddPage
     */
    protected $themerAddPage;

    /**
     * The 'Edit' page of the Paddle Themer module.
     *
     * @var ThemerEditPage
     */
    protected $themerEditPage;

    /**
     * The 'Overview' page of the Paddle Themer module.
     *
     * @var ThemerOverviewPage
     */
    protected $themerOverviewPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The Menu Overview page.
     *
     * @var MenuOverviewPage
     */
    protected $menuOverviewPage;

    /**
     * The alphanumeric test data provider.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;


    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->themerAddPage = new ThemerAddPage($this);
        $this->themerEditPage = new ThemerEditPage($this);
        $this->themerOverviewPage = new ThemerOverviewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->frontPage = new FrontPage($this);
        $this->menuOverviewPage = new MenuOverviewPage($this);

        // Log in as site manager.
        $this->userSessionService->login('SiteManager');

        // Create a theme with vertical navigation and enable it.
        $this->enableVerticalTheme();
    }

    /**
     * Tests the vertical menu.
     *
     * @group themer
     */
    public function testVerticalMenuStyles()
    {
        $parent_page = $this->alphanumericTestDataProvider->getValidValue();
        $child_page = $this->alphanumericTestDataProvider->getValidValue();
        $no_child_page = $this->alphanumericTestDataProvider->getValidValue();
        $parent_id = $this->contentCreationService->createBasicPage($parent_page);
        $this->administrativeNodeViewPage->go($parent_id);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $child_id = $this->contentCreationService->createBasicPage($child_page);
        $this->administrativeNodeViewPage->go($child_id);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $no_child_id = $this->contentCreationService->createBasicPage($no_child_page);
        $this->administrativeNodeViewPage->go($no_child_id);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        // Create three menu items, with the second item being a child of the first.
        $this->menuOverviewPage->go();

        $values = array(
            'title' => $parent_page,
            'parent' => MenuOverviewPage::MAIN_MENU_NAME . ":0",
            'internal_link' => "node/$parent_id",
        );
        $mlid_1 = $this->menuOverviewPage->contextualToolbar->buttonCreateMenuItem->click();
        $this->modalWithAjaxCall($values);

        $values = array(
            'title' => $child_page,
            'parent' => MenuOverviewPage::MAIN_MENU_NAME . ":$mlid_1",
            'internal_link' => "node/$child_id",
        );
        $this->menuOverviewPage->contextualToolbar->buttonCreateMenuItem->click();
        $this->modalWithAjaxCall($values, '--' . $parent_page);

        $values = array(
            'title' => $no_child_page,
            'parent' => MenuOverviewPage::MAIN_MENU_NAME . ":$mlid_1",
            'internal_link' => "node/$no_child_id",
        );

        $this->menuOverviewPage->contextualToolbar->buttonCreateMenuItem->click();
        $this->modalWithAjaxCall($values);
        $this->frontPage->go();

        // make sure children of vertical menu are not visible on front page.
        $this->assertFalse($this->frontPage->checkVerticalMenuExpanded());
        $this->assertTextNotPresent($child_page);

        // Make sure mobile trigger is not visible.
        $this->assertFalse($this->frontPage->mobileMenuTrigger->displayed());

        // Go to the parent page and verify that both menu items are visible.
        $this->frontPage->menuItemCollapsed->click();
        $this->assertTextPresent($parent_page);
        $this->assertTextPresent($child_page);

        $this->frontPage->menuItemNoChildren->click();
        $this->assertTextPresent($parent_page);
        $this->assertTextPresent($no_child_page);
    }

    /**
     * Tests the mobile menu in the Vertical Menu theme.
     *
     * @group themer
     */
    public function testVerticalMenuMobileStyles()
    {
        $parent_page = $this->alphanumericTestDataProvider->getValidValue();
        $child_page = $this->alphanumericTestDataProvider->getValidValue();
        $parent_id = $this->contentCreationService->createBasicPage($parent_page);
        $this->administrativeNodeViewPage->go($parent_id);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $child_id = $this->contentCreationService->createBasicPage($child_page);
        $this->administrativeNodeViewPage->go($child_id);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();

        // Create 2 menu items linking to the nodes where the menu link of node
        // 2 is a child of menu link of node 1.
        $this->menuOverviewPage->go();
        $title_node1 = $this->alphanumericTestDataProvider->getValidValue();
        $values = array(
            'title' => $title_node1,
            'parent' => MenuOverviewPage::MAIN_MENU_NAME . ":0",
            'internal_link' => "node/$parent_id",
        );
        $mlid_1 = $this->menuOverviewPage->contextualToolbar->buttonCreateMenuItem->click();
        $this->modalWithAjaxCall($values);

        $title_node2 = $this->alphanumericTestDataProvider->getValidValue();
        $values = array(
            'title' => $title_node2,
            'parent' => MenuOverviewPage::MAIN_MENU_NAME . ":$mlid_1",
            'internal_link' => "node/$child_id",
        );

        $this->menuOverviewPage->contextualToolbar->buttonCreateMenuItem->click();
        $this->modalWithAjaxCall($values, '--' . $parent_page);
        $this->frontPage->go();
        // Resize the browser window to show the mobile menu trigger.
        $this->resizeCurrentWindow(500);

        $testcase = $this;
        $callable = new SerializableClosure(
            function () use ($testcase) {
                if ($testcase->frontPage->mobileMenuTrigger->displayed()) {
                    return true;
                }
            }
        );
        $this->waitUntil($callable, $this->getTimeout());

        // Open the mobile menu.
        $this->frontPage->mobileMenuTrigger->click();

        // Wait for the menu to be visible again.
        $callable = new SerializableClosure(
            function () use ($testcase) {
                if ($testcase->frontPage->mainMenuVerticalMenu->getWebdriverElement()->displayed()
                ) {
                    return true;
                }
            }
        );
        $this->waitUntil($callable, $this->getTimeout());
        $this->assertTextPresent($parent_page);
        $this->assertTextNotPresent($child_page);
        $this->assertFalse($this->frontPage->checkVerticalMenuExpanded());

        $this->frontPage->go();
        $this->frontPage->checkArrival();
    }

    /*
     * Create and enable a vertical menu based theme.
     */
    protected function enableVerticalTheme()
    {
        // Verify you can create a new theme based on the kanooh_theme_v2_vertical_navigation.
        $this->themerOverviewPage->go();
        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();
        $this->themerAddPage->baseTheme->selectOptionByValue('kanooh_theme_v2_vertical_navigation');
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();
        $theme_name = $this->themerEditPage->getThemeName();
        // Save the theme.
        $this->moveto($this->themerEditPage->buttonSubmit);
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();
        // Enable the theme.
        $this->themerOverviewPage->theme($theme_name)->enable->click();
        $this->themerOverviewPage->checkArrival();
    }

    /**
     * Call the modal window, fill and submit.
     *
     * @param array $values
     *   The values we need to create the menu item - 'title'(mandatory),
     *   'description', 'parent'.
     * @param string $parentTitle
     *   The title of the parent link.
     *
     */
    protected function modalWithAjaxCall($values, $parentTitle = '<Hoofdnavigatie>')
    {
        $Internal_link = $values['internal_link'];
        $modal = new CreateMenuItemModal($this);
        $modal->waitUntilOpened();
        $modal->createMenuItemForm->internalLinkPath->clear();
        $ajax_service = new AjaxService($this);
        $ajax_service->markAsWaitingForAjaxCallback($modal->createMenuItemForm->internalLinkPath->getWebdriverElement());
        $modal->createMenuItemForm->internalLinkPath->fill($Internal_link);

        $autoComplete = new AutoComplete($this);
        $autoComplete->waitUntilSuggestionCountEquals(1);

        // Use the arrow keys to select the result, and press enter to confirm.
        $this->keys(Keys::DOWN . Keys::ENTER);
        $modal->createMenuItemForm->internalLinkRadioButton->select();
        $ajax_service->waitForAjaxCallback($modal->createMenuItemForm->internalLinkPath->getWebdriverElement());
        $modal->createMenuItemForm->navigation->selectOptionByLabel($parentTitle);

        $modal->submit();
        $modal->waitUntilClosed();
    }
}
