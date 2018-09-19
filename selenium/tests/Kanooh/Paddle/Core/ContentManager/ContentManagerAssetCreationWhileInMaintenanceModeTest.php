<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentManager\ContentManagerAssetCreationWhileInMaintenanceModeTest.
 */

namespace Kanooh\Paddle\Core\ContentManager;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Pages\Admin\ContentManager\AssetsPage\AssetsPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Element\Scald\AddAtomModal;
use Kanooh\Paddle\Pages\Element\Scald\Image\AddOptionsModal as AddImageOptionsModal;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\Paddle\Utilities\HttpRequest\HttpRequest;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Make sure asset creation keeps working in Paddle maintenance mode.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ContentManagerAssetCreationWhileInMaintenanceModeTest extends WebDriverTestCase
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

        $drupal = new DrupalService();
        $drupal->bootstrap($this);

        // Enable Paddle maintenance mode.
        variable_set('paddle_maintenance_mode', 1);

        // Log in as an editor.
        $this->userSessionService->login('Editor');
    }

    /**
     * Test thumbnail creation after uploading an image to the media library.
     *
     * @group regression
     * @group scald
     */
    public function testImageThumbnailCreation()
    {
        $this->addContentPage->go();
        $this->addContentPage->links->linkAssetTypeImage->click();

        $add_modal = new AddAtomModal($this);
        $add_modal->waitUntilOpened();
        // The regression was on image files with a lower case file extension.
        // So, be sure to use such a file.
        $resource = $this->file(dirname(__FILE__) . '/../../assets/sample_image.jpg');
        $add_modal->form->fileList->uploadFiles($resource);

        $options_modal = new AddImageOptionsModal($this);
        $options_modal->waitUntilOpened();

        $this->assertImageRequestSucceeds($options_modal->form->thumbnail);

        $title = $this->random->name(6);
        $options_modal->form->title->fill($title);
        $options_modal->form->alternativeText->fill($this->random->name(6));
        $options_modal->form->finishButton->click();
        $options_modal->waitUntilClosed();

        // Make sure we are redirected to the Media Library page.
        $this->assetsPage->checkArrival();
        $this->assetCreationService->waitUntilAtomCreated($title, 'Image');
        // Save the atom id for cleanup at tear down.
        $atom_id = $this->assetCreationService->getCreatedAtomId();

        $this->assertImageRequestSucceeds($this->assetsPage->library->getAtomById($atom_id)->image);
    }

    /**
     * Make sure the image can be retrieved without HTTP error codes.
     *
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $image
     */
    public function assertImageRequestSucceeds($image)
    {
        $request = new HttpRequest($this);
        $request->setMethod(HttpRequest::GET);
        $request->setUrl($image->attribute('src'));
        $response = $request->send();
        $this->assertEquals(200, $response->status);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Delete all assets created during the test.
        AssetCreationService::cleanUp($this);

        // Revert to the default Paddle Maintenance Mode setting for Selenium
        // tests like profiles/paddle/post_install_selenium.sh does so tests
        // run after this test can still rely on that default.
        variable_set('paddle_maintenance_mode', 0);

        parent::tearDown();
    }
}
