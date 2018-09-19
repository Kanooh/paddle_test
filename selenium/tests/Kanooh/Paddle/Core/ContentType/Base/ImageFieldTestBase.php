<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\Base\ImageFieldTestBase.
 */

namespace Kanooh\Paddle\Core\ContentType\Base;

use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminNodeViewPage;
use Kanooh\Paddle\Pages\Element\Scald\ImageAtomField;
use Kanooh\Paddle\Pages\Element\Scald\LibraryModal;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalApi\DrupalAjaxApi;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class ImageFieldTestBase
 * @package Kanooh\Paddle\Core\ContentType\Base
 */
abstract class ImageFieldTestBase extends WebDriverTestCase
{

    /**
     * @var AdminNodeViewPage
     */
    protected $adminNodeViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * The edit page of a node.
     *
     * @var EditPage
     */
    protected $editPage;

    /**
     * Creates a node of the content type that is being tested.
     *
     * @param string $title
     *   Optional title for the node. If omitted a random title will be used.
     *
     * @return int
     *   The node ID of the node that was created.
     */
    abstract public function setupNode($title = null);

    /**
     * Get the edit page for a specific content type.
     *
     * @return EditPage
     *   The edit page.
     */
    abstract public function getEditPage();

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->adminNodeViewPage = new AdminNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->assetCreationService = new AssetCreationService($this);
        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->editPage = new EditPage($this);

        // Go to the login page and log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Log out
        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->logout();

        parent::tearDown();
    }

    /**
     * Tests the image field of a content type.
     *
     * @group scald
     * @group manualCrop
     */
    public function testImageField()
    {
        // Create a node and an asset.
        $nid = $this->setupNode();

        // Create an image atom with a crop style.
        $data = array(
          'path' => dirname(__FILE__) . '/../../../assets/budapest.jpg',
          'image_style' => '16_9',
        );
        $data = $this->assetCreationService->createImage($data);

        // Gp to the edit page and an image with a cropping ratio.
        $edit_page = $this->getEditPage();
        $edit_page->go($nid);

        $atom_field = $edit_page->getImageAtomField();
        /** @var ImageAtomField $atom_field */
        $atom_field->selectButton->click();

        $library_modal = new LibraryModal($this);
        $library_modal->waitUntilOpened();
        $atom = $library_modal->library->getAtomById($data['id']);
        $atom->insertLink->click();
        $library_modal->waitUntilClosed();

        $drupalAjaxApi = new DrupalAjaxApi($this);
        $atom_field->style->selectOptionByValue($data['image_style']);
        $drupalAjaxApi->waitUntilElementFinishedAjaxing($atom_field->style->getWebdriverElement());

        $edit_page->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Generate the path to the style image.
        $scald_atom = scald_atom_load($data['id']);
        $expected_src = file_create_url(image_style_path($data['image_style'], $scald_atom->file_source));
        $image = $this->byXPath('//div[contains(@class, "field-type-paddle-scald-atom")]//img');

        // The images may the 'itok' query parameter appended to the url.
        // Assert that the string starts with the expected path.
        $this->assertStringStartsWith($expected_src, $image->attribute('src'));
    }
}
