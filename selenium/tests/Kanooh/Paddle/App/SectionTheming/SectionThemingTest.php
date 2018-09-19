<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\SectionTheming\SectionThemingTest.
 */

namespace Kanooh\Paddle\App\SectionTheming;

use Kanooh\Paddle\Apps\SectionTheming;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage\MenuOverviewPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerAddPage\ThemerAddPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ThemerEditPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\MenuCreationService;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on the Section theming module.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class SectionThemingTest extends WebDriverTestCase
{
    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * @var CleanUpService
     */
    protected $cleanUpService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var MenuCreationService
     */
    protected $menuCreationService;

    /**
     * @var MenuOverviewPage
     */
    protected $menuOverviewPage;

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
     * @var ViewPage
     */
    protected $viewPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->assetCreationService = new AssetCreationService($this);
        $this->cleanUpService = new CleanUpService($this);
        $this->menuCreationService = new MenuCreationService($this);
        $this->menuOverviewPage = new MenuOverviewPage($this);
        $this->themerAddPage = new ThemerAddPage($this);
        $this->themerEditPage = new ThemerEditPage($this);
        $this->themerOverviewPage = new ThemerOverviewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->viewPage = new ViewPage($this);
        $this->appService = new AppService($this, $this->userSessionService);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as Chief Editor.
        $this->userSessionService->login('SiteManager');

        // Enable the Section theming module.
        $this->appService->enableApp(new SectionTheming);
    }

    /**
     * Tests the background images per menu functionality.
     *
     * In this test case we have 5 pages which we will test, each presenting
     * another use case:
     * Page A will be linked to a single menu item. We test if the background
     * is shown on that page if linked to that item.
     * Page B has two children called C and D. We will upload images to B and C.
     * This we do to see if C will have a different background image than B AND
     * to see if D will inherit the background of B. E we will not give a
     * background image at all, to see if it retrieves the background image
     * set by the theme.
     */
    public function testBackgroundImagePerMenu()
    {
        // Cleanup existing menu items.
        $this->cleanUpService->deleteMenuItems();
        $this->cleanUpService->deleteEntities('node', 'basic_page');

        // Create 5 Basic pages A, B, C, D and E.
        $page_titles = array('A', 'B', 'C', 'D', 'E');

        foreach ($page_titles as $page_title) {
            $page_titles[$page_title]['nid'] = $this->contentCreationService->createBasicPage($page_title);
        }

        // Create all the menu items. A and E have no children.
        // C and D are the children of B.
        $page_titles['A']['mlid'] = $this->menuCreationService->createNodeMenuItem(
            $page_titles['A']['nid'],
            'main_menu_nl',
            'A'
        );
        $page_titles['B']['mlid'] = $this->menuCreationService->createNodeMenuItem(
            $page_titles['B']['nid'],
            'main_menu_nl',
            'B'
        );
        $page_titles['C']['mlid'] = $this->menuCreationService->createNodeMenuItem(
            $page_titles['C']['nid'],
            'main_menu_nl',
            'C',
            $page_titles['B']['mlid']
        );
        $page_titles['D']['mlid'] = $this->menuCreationService->createNodeMenuItem(
            $page_titles['D']['nid'],
            'main_menu_nl',
            'D',
            $page_titles['B']['mlid']
        );
        $page_titles['E']['mlid'] = $this->menuCreationService->createNodeMenuItem(
            $page_titles['E']['nid'],
            'E',
            'main_menu_nl'
        );

        // Add a header background in the theme.
        $theme_background_image_url = $this->addBackgroundImageToTheme();

        // Edit menu items A, B and C and add a background image.
        $page_titles['A']['file_url'] = $this->addImageToMenuItem('A', $page_titles['A']['mlid']);
        $page_titles['B']['file_url'] = $this->addImageToMenuItem('B', $page_titles['B']['mlid']);
        $page_titles['C']['file_url'] = $this->addImageToMenuItem('C', $page_titles['C']['mlid'], $page_titles['B']['mlid']);

        // Time to test the front-end.
        // Assess that image A is shown on page A.
        $this->assertImageShownInHeader($page_titles['A']['file_url'], $page_titles['A']['nid']);

        // Assess that image B is shown on page B.
        $this->assertImageShownInHeader($page_titles['B']['file_url'], $page_titles['B']['nid']);

        // Assess that image C is shown on page C, but B is not.
        $this->assertImageShownInHeader($page_titles['C']['file_url'], $page_titles['C']['nid']);
        $this->assertImageNotShownInHeader($page_titles['B']['file_url'], $page_titles['C']['nid']);

        // Assess that image B is shown on page D.
        $this->assertImageShownInHeader($page_titles['B']['file_url'], $page_titles['D']['nid']);

        // Assess that that the background image set in the theme is shown on page E.
        $this->assertImageShownInHeader($theme_background_image_url, $page_titles['E']['nid']);
    }

    /**
     * Adds an header background image to a menu item.
     *
     * @param $page_title
     *   The title of the page which is linked to the menu item.
     * @param $mlid
     *   The Identifier of the menu item.
     *
     * @return bool|string
     *   The URL of the created header background image, or FALSE if something went wrong.
     */
    protected function addImageToMenuItem($page_title, $mlid, $parent_mlid = 0)
    {
        $data = array(
            'path' => dirname(__FILE__) . '/../../assets/' . $page_title . '.jpg',
            'image_style' => '16_9',
        );
        $image = $this->assetCreationService->createImage($data);

        $this->menuOverviewPage->go();

        if (!empty($parent_mlid)) {
            $this->menuOverviewPage->overviewForm->openTreeToMenuItem(array($parent_mlid), $page_title);
        }

        $this->menuOverviewPage->editMenuItem($mlid, array('backgroundImage' => $image['id']));

        $atom = scald_atom_load($image['id']);
        $atom_path = $atom->thumbnail_source;
        $styled_path = image_style_path('large', $atom_path);
        // Return the 'large' path for later use.
        return file_create_url($styled_path);
    }

    /**
     * Checks if the image is displayed in the header of the given page.
     *
     * @param string $image_url
     *   The URL of the header background image.
     * @param string $nid
     *   The Identifier of the given page.
     */
    protected function assertImageShownInHeader($image_url, $nid)
    {
        $this->viewPage->go($nid);

        $link = $this->byCss('header > div.header-background-canvas');
        $this->assertEquals('url("' . $image_url . '")', $link->css('background-image'));
    }

    /**
     * Checks if the image is NOT displayed in the header of the given page.
     *
     * @param string $image_url
     *   The URL of the header background image.
     * @param string $nid
     *   The Identifier of the given page.
     */
    protected function assertImageNotShownInHeader($image_url, $nid)
    {
        $this->viewPage->go($nid);

        $link = $this->byCss('header > div.header-background-canvas');
        $this->assertNotEquals($image_url, $link->css('background-image'));
    }

    /**
     * Adds a background image to the theme and enables the theme.
     *
     * @return string
     *   Returns the URL of the background image.
     */
    protected function addBackgroundImageToTheme()
    {
        // Go to the themer overview page.
        $this->themerOverviewPage->go();

        // Create a new theme.
        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();

        // Pick up a random name and the base theme.
        $this->themerAddPage->baseTheme->selectOptionByValue('vo_standard');
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();

        // Retrieve the machine name of the theme.
        $theme_name = $this->themerEditPage->getThemeName();

        // Unfold the header section and upload a header image.
        $this->themerEditPage->header->header->click();
        $this->themerEditPage->header->headerPatternRadios->uploadImage->click();
        $this->themerEditPage->header->headerImage->waitUntilDisplayed();

        // Set a header image and enable the theme.
        $file_path = $this->file(dirname(__FILE__) . '/../../assets/london.jpg');
        $this->themerEditPage->header->headerImage->chooseFile($file_path);
        $this->themerEditPage->header->headerImage->uploadButton->click();
        $this->themerEditPage->header->headerImage->waitUntilFileUploaded();
        $file_link = $this->themerEditPage->header->headerImage->getFileLink()->attribute('href');
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();
        $this->themerOverviewPage->theme($theme_name)->enable->click();
        $this->themerOverviewPage->checkArrival();

        return $file_link;
    }
}
