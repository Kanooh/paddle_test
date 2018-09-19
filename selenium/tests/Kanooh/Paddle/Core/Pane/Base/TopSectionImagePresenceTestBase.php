<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\Base\TopSectionImagePresenceTestBase.
 */

namespace Kanooh\Paddle\Core\Pane\Base;

use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminNodeViewPage;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\Pane\Pane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\SectionedPanelsContentType;
use Kanooh\Paddle\Pages\Element\Scald\LibraryModal;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

abstract class TopSectionImagePresenceTestBase extends WebDriverTestCase
{

    /**
     * @var AddPaneModal
     */
    protected $addPaneModal;

    /**
     * @var AdminNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var int
     */
    protected $atomId;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var ViewPage
     */
    protected $frontendViewPage;

    /**
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * Creates a pane, adds top image to it and returns its uuid.
     *
     * @param int $nid
     *   The nid of the page to add the pane to.
     *
     * @return string
     *   The uuid of the created pane.
     */
    abstract public function createPaneWithTopImage($nid);

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->addPaneModal = new AddPaneModal($this);
        $this->administrativeNodeViewPage = new AdminNodeViewPage($this);
        $this->frontendViewPage = new ViewPage($this);
        $this->layoutPage = new LayoutPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Go to the login page and log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Create a image atom to use for the top section.
        $asset_service = new AssetCreationService($this);
        $data = $asset_service->createImage();
        $this->atomId = $data['id'];
    }

    /**
     * Test that the top section is displayed when the content is image.
     *
     * @group regression
     * @group topSectionImage
     */
    public function testTopImagePresent()
    {
        // Create a test page.
        $nid = $this->contentCreationService->createBasicPage();

        // Create a pane from the type we are testing and add top image to it.
        $pane_uuid = $this->createPaneWithTopImage($nid);

        // Go to the front-end and make sure the top image is displayed.
        $this->frontendViewPage->go($nid);
        $pane = new Pane($this, $pane_uuid, '//div[@data-pane-uuid = "' . $pane_uuid . '"]');
        $this->assertNotNull($pane->topSection->getSectionImage());
    }

    /**
     * Enables the top section for a pane and adds an image to it.
     *
     * @param Pane $pane
     *   The Pane object for this pane which represents it rendered.
     * @param SectionedPanelsContentType $content_type
     *   The object for the pane representing it when edited.
     */
    protected function addTopImageToPane($pane, $content_type)
    {
        $pane->toolbar->buttonEdit->click();
        $pane->editPaneModal->waitUntilOpened();
        $content_type->topSection->enable->check();
        $content_type->topSection->contentTypeRadios->image->select();
        $content_type->topSection->image->selectButton->click();

        $library_modal = new LibraryModal($this);
        $library_modal->waitUntilOpened();
        $library_modal->library->getAtomById($this->atomId)->insertLink->click();
        $library_modal->waitUntilClosed();

        $pane->editPaneModal->submit();
    }
}
