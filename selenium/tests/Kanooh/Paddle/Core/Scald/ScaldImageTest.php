<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Scald\ScaldImageTest.php
 */

namespace Kanooh\Paddle\Core\Scald;

use Kanooh\Paddle\Pages\Admin\ContentManager\AssetsPage\AssetsPage;
use Kanooh\Paddle\Pages\Element\Scald\AddAssetModal;
use Kanooh\Paddle\Pages\Element\Scald\AddAtomModal;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * ScaldImageTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ScaldImageTest extends WebDriverTestCase
{
    /**
     * The assets library page.
     *
     * @var AssetsPage
     */
    protected $assetsPage;

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
        $this->assetsPage = new AssetsPage($this);
        $this->userSessionService = new UserSessionService($this);

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Tests the author and tags field presence.
     *
     * @group modals
     * @group scald
     */
    public function testTagsAndAuthorFieldsNotPresent()
    {
        $this->assetsPage->go();

        // Add a new asset.
        $this->assetsPage->contextualToolbar->buttonAddNewAsset->click();
        $add_asset_modal = new AddAssetModal($this);
        $add_asset_modal->waitUntilOpened();
        $add_asset_modal->imageLink->click();
        $add_image_modal = new AddAtomModal($this);
        $add_image_modal->waitUntilOpened();

        // Verify that the fields are not there.
        $this->assertFalse($this->isElementByPropertyPresent('name', 'scald_authors'));
        $this->assertFalse($this->isElementByPropertyPresent('name', 'scald_tags'));
        $add_image_modal->form->cancelButton->click();
        $add_image_modal->waitUntilClosed();
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        parent::tearDown();
    }
}
