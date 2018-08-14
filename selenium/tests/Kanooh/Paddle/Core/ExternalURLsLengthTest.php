<?php

/**
 * @file
 * Contains \Kanooh\Paddle\ExternalURLsLengthTest.
 */

namespace Kanooh\Paddle\Core;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\CreateMenuItemModal;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage\MenuOverviewPage;
use Kanooh\Paddle\Pages\Element\Pane\CustomContentPane;
use Kanooh\Paddle\Pages\Element\Pane\ImagePane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ImagePanelsContentType;
use Kanooh\Paddle\Pages\Element\Region\Region;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests that the length of external URLs can be up to 256 characters.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ExternalURLsLengthTest extends WebDriverTestCase
{
    /**
     * The administrative node view of a page.
     *
     * @var ViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * The alphanumeric test data generator.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * The service to create content of several types.
     *
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The panels display of a page.
     *
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * @var string
     */
    protected $longUrl;

    /**
     * The menu overview page.
     *
     * @var MenuOverviewPage
     */
    protected $menuOverviewPage;

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
        $this->administrativeNodeViewPage = new ViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->layoutPage = new LayoutPage($this);
        $this->menuOverviewPage = new MenuOverviewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        $drupal = new DrupalService();
        $drupal->bootstrap($this);

        // Initialize an URL with total length of 253 characters which is the maximum allowed.
        // See https://en.wikipedia.org/wiki/Domain_Name_System
        $this->longUrl = strtolower('http://' . // 7 characters
            $this->alphanumericTestDataProvider->getValidValue(60) . "." . // 61 characters.
            $this->alphanumericTestDataProvider->getValidValue(60) . "." . // 61 characters.
            $this->alphanumericTestDataProvider->getValidValue(60) . "." . // 61 characters.
            $this->alphanumericTestDataProvider->getValidValue(59) . '.com'); // 64 characters.


        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Tests length of the external URL on the image pane.
     */
    public function testImagePaneExternalURLs()
    {
        $asset_creation_service = new AssetCreationService($this);
        $data = $asset_creation_service->createImage();
        $nid = $this->contentCreationService->createBasicPage();
        $this->layoutPage->go($nid);
        /** @var Region $region */
        $region = $this->layoutPage->display->getRandomRegion();

        $image_type = new ImagePanelsContentType($this);
        $url = $this->longUrl;
        $atom_id = $data['id'];
        $callable = new SerializableClosure(
            function () use ($image_type, $url, $atom_id) {
                $image_type->getForm()->external->select();
                $image_type->getForm()->externalUrl->fill($url);
                $image_type->getForm()->image->selectAtom($atom_id);
            }
        );
        $pane = $region->addPane($image_type, $callable);
        $pane_uuid = $pane->getUuid();
        $pane_xpath = $pane->getXPathSelector();
        $image_pane = new ImagePane($this, $pane_uuid, $pane_xpath);

        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $image_pane->checkImageLink($this->longUrl);
    }

    /**
     * Tests length of the external URL on the pane sections.
     */
    public function testPaneSectionsExternalURLs()
    {
        $nid = $this->contentCreationService->createBasicPage();
        $this->layoutPage->go($nid);
        /** @var Region $region */
        $region = $this->layoutPage->display->getRandomRegion();

        $custom_content_pane = new CustomContentPanelsContentType($this);
        $url = $this->longUrl;
        $webdriver = $this;
        $callable = new SerializableClosure(
            function () use ($webdriver, $custom_content_pane, $url) {
                foreach (array('topSection', 'bottomSection') as $section) {
                    $custom_content_pane->{"$section"}->enable->check();
                    $text = $webdriver->alphanumericTestDataProvider->getValidValue();
                    $custom_content_pane->{"$section"}->text->fill($text);
                    $custom_content_pane->{"$section"}->urlTypeRadios->external->select();
                    $custom_content_pane->{"$section"}->externalUrl->fill($url);
                }
            }
        );
        $pane = $region->addPane($custom_content_pane, $callable);
        $pane_uuid = $pane->getUuid();

        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $pane_display = new CustomContentPane($this, $pane_uuid, '//*[@data-pane-uuid="' . $pane_uuid . '"]');
        $this->assertEquals($this->longUrl, trim($pane_display->topSection->linkUrl->attribute('href'), '/'));
        $this->assertEquals($this->longUrl, trim($pane_display->bottomSection->linkUrl->attribute('href'), '/'));
    }

    /**
     * Tests length of the external URL for menu items.
     */
    public function testMenuItemExternalURLLength()
    {
        $this->menuOverviewPage->go();

        // Add a menu item with external path.
        $values = array(
            'title' => $this->alphanumericTestDataProvider->getValidValue(),
            'external_link' => $this->longUrl,
        );
        $mlid = $this->menuOverviewPage->createMenuItem($values);

        $item_row = $this->menuOverviewPage->overviewForm->overviewFormTable->getMenuItemRowByMlid($mlid);
        $item_row->linkEditMenuItem->click();

        $modal = new CreateMenuItemModal($this);
        $modal->waitUntilOpened();

        // Check that it was saved correctly.
        $this->assertEquals($this->longUrl, $modal->createMenuItemForm->externalLinkPath->getContent());
        $modal->close();
    }
}
