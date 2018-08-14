<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentManager\ContentManagerAssetCreationTest.
 */

namespace Kanooh\Paddle\Core\ContentManager;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Pages\Admin\ContentManager\AssetsPage\AssetsPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Element\Scald\AddAtomModal;
use Kanooh\Paddle\Pages\Element\Scald\Document\AddOptionsModal as AddFileOptionsModal;
use Kanooh\Paddle\Pages\Element\Scald\Image\AddOptionsModal as AddImageOptionsModal;
use Kanooh\Paddle\Pages\Element\Scald\MovieFile\AddOptionsModal as AddVideoFileOptionsModal;
use Kanooh\Paddle\Pages\Element\Scald\MovieYoutube\AddModal;
use Kanooh\Paddle\Pages\Element\Scald\MovieYoutube\AddOptionsModal as AddVideoYoutubeOptionsModal;
use Kanooh\Paddle\Pages\Element\Scald\SourceModal;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests on the Create Assets links on Paddle Content Manager page.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ContentManagerAssetCreationTest extends WebDriverTestCase
{

    /**
     * The Add Content page.
     *
     * @var AddPage
     */
    protected $addContentPage;

    /**
     * The assets library page.
     *
     * @var AssetsPage
     */
    protected $assetsPage;

    /**
     * @var Random
     */
    protected $random;

    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

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
        $this->addContentPage = new AddPage($this);
        $this->assetsPage = new AssetsPage($this);
        $this->random = new Random();
        $this->assetCreationService = new AssetCreationService($this);
        $this->userSessionService = new UserSessionService($this);

        // Log in as an editor.
        $this->userSessionService->login('Editor');
    }

    /**
     * Tests the creation of File assets from the "Add Content" page.
     *
     * @group workflow
     * @group scald
     */
    public function testFileCreationOnAddContentPage()
    {
        // Create a file asset from the "Add Content" page.
        $this->addContentPage->go();
        $this->addContentPage->links->linkAssetTypeFile->click();

        $add_modal = new AddAtomModal($this);
        $add_modal->waitUntilOpened();
        $resource = $this->file(dirname(__FILE__) . '/../../assets/pdf-sample.pdf');
        $add_modal->form->fileList->uploadFiles($resource);

        $options_modal = new AddFileOptionsModal($this);
        $options_modal->waitUntilOpened();
        $title = $this->random->name(6);
        $options_modal->form->title->fill($title);
        $options_modal->form->finishButton->click();
        $options_modal->waitUntilClosed();

        // Make sure we are redirected to the Media Library page.
        $this->assetsPage->checkArrival();
        $this->assetCreationService->waitUntilAtomCreated($title, 'File');
        // Save the atom id for cleanup at tear down.
        $this->assetCreationService->getCreatedAtomId();

        // Make sure that the asset was created. Checking the title should
        // be enough.
        $this->assertEquals($title, $this->assetsPage->library->items[0]->title);
    }

    /**
     * Tests the creation of Image assets from the "Add Content" page.
     *
     * @group workflow
     * @group scald
     */
    public function testImageCreationOnAddContentPage()
    {
        $this->addContentPage->go();
        $this->addContentPage->links->linkAssetTypeImage->click();

        $add_modal = new AddAtomModal($this);
        $add_modal->waitUntilOpened();
        $resource = $this->file(dirname(__FILE__) . '/../../assets/sample_image.jpg');
        $add_modal->form->fileList->uploadFiles($resource);

        $options_modal = new AddImageOptionsModal($this);
        $options_modal->waitUntilOpened();
        $title = $this->random->name(6);
        $options_modal->form->title->fill($title);
        $options_modal->form->alternativeText->fill($this->random->name(6));
        $options_modal->form->finishButton->click();
        $options_modal->waitUntilClosed();

        // Make sure we are redirected to the Media Library page.
        $this->assetsPage->checkArrival();
        $this->assetCreationService->waitUntilAtomCreated($title, 'Image');
        // Save the atom id for cleanup at tear down.
        $this->assetCreationService->getCreatedAtomId();

        // Make sure that the asset was created. Checking the title should
        // be enough.
        $this->assertEquals($title, $this->assetsPage->library->items[0]->title);
    }

    /**
     * Tests the creation of Video File assets from the "Add Content" page.
     *
     * @group workflow
     * @group scald
     */
    public function testVideoFileCreationOnAddContentPage()
    {
        $this->addContentPage->go();
        $this->addContentPage->links->linkAssetTypeVideo->click();
        $source_modal = new SourceModal($this);
        $source_modal->waitUntilOpened();
        $source_modal->chooseSource("paddle_scald_video_file");

        $add_modal = new AddAtomModal($this);
        $add_modal->waitUntilOpened();
        $resource = $this->file(dirname(__FILE__) . '/../../assets/sample_video.mp4');
        $add_modal->form->fileList->uploadFiles($resource);

        $options_modal = new AddVideoFileOptionsModal($this);
        $options_modal->waitUntilOpened();
        $title = $this->random->name(6);
        $options_modal->form->title->fill($title);
        $options_modal->form->width->fill(400);
        $options_modal->form->height->fill(400);
        $options_modal->form->finishButton->click();
        $options_modal->waitUntilClosed();

        // Make sure we are redirected to the Media Library page.
        $this->assetsPage->checkArrival();
        $this->assetCreationService->waitUntilAtomCreated($title, 'Video');
        // Save the atom id for cleanup at tear down.
        $this->assetCreationService->getCreatedAtomId();

        // Make sure that the asset was created. Checking the title should
        // be enough.
        $this->assertEquals($title, $this->assetsPage->library->items[0]->title);
    }

    /**
     * Tests the creation of Youtube Video assets from the "Add Content" page.
     *
     * @group workflow
     * @group scald
     */
    public function testYoutubeVideoCreationOnAddContentPage()
    {
        $this->addContentPage->go();
        $this->addContentPage->links->linkAssetTypeVideo->click();
        $source_modal = new SourceModal($this);
        $source_modal->waitUntilOpened();
        $source_modal->chooseSource("paddle_scald_youtube");

        $add_modal = new AddModal($this);
        $add_modal->waitUntilOpened();
        $add_modal->form->url->fill('https://www.youtube.com/watch?v=aTMbHEoAktM');
        $add_modal->form->continueButton->click();

        $options_modal = new AddVideoYoutubeOptionsModal($this);
        $options_modal->waitUntilOpened();
        $title = $this->random->name(6);
        $options_modal->form->title->fill($title);
        $options_modal->form->finishButton->click();
        $options_modal->waitUntilClosed();

        // Make sure we are redirected to the Media Library page.
        $this->assetsPage->checkArrival();
        $this->assetCreationService->waitUntilAtomCreated($title, 'Video');
        // Save the atom id for cleanup at tear down.
        $this->assetCreationService->getCreatedAtomId();

        // Make sure that the asset was created. Checking the title should
        // be enough.
        $this->assertEquals($title, $this->assetsPage->library->items[0]->title);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Delete any assets created during the tests.
        AssetCreationService::cleanUp($this);
        parent::tearDown();
    }
}
