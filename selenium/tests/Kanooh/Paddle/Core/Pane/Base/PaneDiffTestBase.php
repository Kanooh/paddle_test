<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\Base\PaneDiffTestBase.
 */

namespace Kanooh\Paddle\Core\Pane\Base;

use Kanooh\Paddle\Pages\Admin\ContentManager\Node\DiffPage\DiffPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\RevisionsPage\RevisionsPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminViewPage;
use Kanooh\Paddle\Pages\Element\Pane\Pane;
use Kanooh\Paddle\Pages\Element\Region\Region;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\ScaldService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

abstract class PaneDiffTestBase extends WebDriverTestCase
{
    /**
     * @var AdminViewPage
     */
    protected $adminViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var DiffPage
     */
    protected $diffPage;

    /**
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * @var RevisionsPage
     */
    protected $revisionsPage;

    /**
     * @var ScaldService
     */
    protected $scaldService;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * Adds a pane with configuration to a region.
     *
     * @param \Kanooh\Paddle\Pages\Element\Region\Region $region
     *   The region to add the pane to.
     *
     * @return Pane
     *   The pane we just added
     */
    abstract public function addPaneToRegion(Region $region);

    /**
     * Edits the pane configuration.
     *
     * @param \Kanooh\Paddle\Pages\Element\Pane\Pane $pane
     *   The pane to edit.
     */
    abstract public function editPane(Pane $pane);

    /**
     * Removes the pane.
     *
     * @param \Kanooh\Paddle\Pages\Element\Pane\Pane $pane
     *   The pane to remove.
     */
    abstract public function removePane(Pane $pane);

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->adminViewPage = new AdminViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->assetCreationService = new AssetCreationService($this);
        $this->diffPage = new DiffPage($this);
        $this->layoutPage = new LayoutPage($this);
        $this->revisionsPage = new RevisionsPage($this);
        $this->scaldService = new ScaldService($this);

        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Go to the login page and log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Tests the pane difference.
     *
     * @group revisions
     */
    public function testPaneDiff()
    {
        $nid = $this->contentCreationService->createBasicPage();

        // Test the diff when adding a pane.
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();

        $pane = $this->addPaneToRegion($region);
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        $this->adminViewPage->revisionsLink->click();
        $this->revisionsPage->checkArrival();
        $this->revisionsPage->contextualToolbar->buttonCompare->click();
        $this->diffPage->checkArrival();

        $this->assertTextPresent('Changes to pane ' . $pane->getUuid());
        foreach ($this->config['old'] as $setting) {
            $this->assertTrue($this->diffPage->checkExactTextAddedPresent($setting), "'$setting' diff was not found.");
        }

        // Test the diff when updating a pane.
        $this->layoutPage->go($nid);
        $this->editPane($pane);
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        $this->adminViewPage->revisionsLink->click();
        $this->revisionsPage->checkArrival();
        $this->revisionsPage->contextualToolbar->buttonCompare->click();
        $this->diffPage->checkArrival();

        $this->assertTextPresent('Changes to pane ' . $pane->getUuid());

        foreach ($this->config['old'] as $setting) {
            $this->assertTrue(
                $this->diffPage->checkExactTextDeletedPresent($setting),
                "'$setting' diff was not found."
            );
        }

        foreach ($this->config['new'] as $setting) {
            $this->assertTrue($this->diffPage->checkExactTextAddedPresent($setting), "'$setting' diff was not found.");
        }

        // Test the diff when removing the pane.
        $this->layoutPage->go($nid);

        $this->removePane($pane);
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        $this->adminViewPage->revisionsLink->click();
        $this->revisionsPage->checkArrival();
        $this->revisionsPage->contextualToolbar->buttonCompare->click();
        $this->diffPage->checkArrival();

        $this->assertTextPresent('Changes to pane ' . $pane->getUuid());
        foreach ($this->config['new'] as $setting) {
            $this->assertTrue($this->diffPage->checkExactTextDeletedPresent($setting), "'$setting' diff was not found.");
        }
    }
}
