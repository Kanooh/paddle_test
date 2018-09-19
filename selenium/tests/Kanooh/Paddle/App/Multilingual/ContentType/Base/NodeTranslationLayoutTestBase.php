<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Multilingual\ContentType\Base\NodeTranslationLayoutTestBase.
 */

namespace Kanooh\Paddle\App\Multilingual\ContentType\Base;

use Kanooh\Paddle\Apps\Multilingual;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Node\TranslatePage\TranslatePage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\MultilingualService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests inheritance of panelizer display settings when translating a node.
 *
 * @package Kanooh\Paddle\App\Multilingual\ContentType\Base
 */
abstract class NodeTranslationLayoutTestBase extends WebDriverTestCase
{

    /**
     * @var ViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The class instance of the layout page for the current content type.
     *
     * @var LayoutPage|PanelsContentPage
     */
    protected $layoutPage;

    /**
     * @var TranslatePage
     */
    protected $translatePage;

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
        $this->administrativeNodeViewPage = new ViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->translatePage = new TranslatePage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as a site manager.
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Multilingual);

        // Make sure the tests have the expected multilingual configuration.
        MultilingualService::setPaddleTestDefaults($this);
    }

    /**
     * Creates a node of the content type that is being tested.
     *
     * @return int
     *   The node ID of the node that was created.
     */
    abstract public function setUpNode();

    /**
     * Fills the create node translation modal.
     *
     * @param null|string $title
     *   The title to use for the node.
     */
    abstract public function fillTranslationModal($title = null);

    /**
     * Tests the inheritance of the translated node layout.
     *
     * @group NodeTranslationLayout
     */
    public function testLayoutInheritance()
    {
        // Enable two languages to translate the nodes to.
        MultilingualService::enableLanguages($this, array('Bulgarian', 'Italian'));

        // Create the test node.
        $nid = $this->setUpNode();

        // Go to the layout page and get the layout used for the source node.
        $this->layoutPage->go($nid);
        $source_layout = $this->layoutPage->display->getCurrentLayoutId();
        $this->layoutPage->contextualToolbar->buttonBack->click();
        $this->acceptAlert();
        $this->administrativeNodeViewPage->checkArrival();

        // Translate the node to Bulgarian.
        $this->translatePage->go($nid);
        $this->translatePage->translationTable->getRowByLanguage('bg')->translationLink->click();
        $this->fillTranslationModal();
        $this->administrativeNodeViewPage->checkArrival();

        // Assert that the layout is the same as the source node.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageLayout->click();
        $this->layoutPage->checkArrival();
        $translation_layout = $this->layoutPage->display->getCurrentLayoutId();
        $this->assertEquals($source_layout, $translation_layout);

        // We cannot go directly to the source node layout page as the alert
        // will block our request.
        $this->layoutPage->contextualToolbar->buttonBack->click();
        $this->acceptAlert();
        $this->administrativeNodeViewPage->checkArrival();

        // Change the source node layout.
        $this->layoutPage->go($nid);
        $allowed_layouts = $this->layoutPage->display->getSupportedLayouts();
        unset($allowed_layouts[$source_layout]);
        $new_source_layout = array_rand($allowed_layouts);
        $this->layoutPage->changeLayout($new_source_layout);
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Translate the node in Italian now.
        $this->translatePage->go($nid);
        $this->translatePage->translationTable->getRowByLanguage('it')->translationLink->click();
        $this->fillTranslationModal();
        $this->administrativeNodeViewPage->checkArrival();

        // Assert that the layout is the same as the source node.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPageLayout->click();
        $this->layoutPage->checkArrival();
        $translation_layout = $this->layoutPage->display->getCurrentLayoutId();
        $this->assertEquals($new_source_layout, $translation_layout);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->contentCreationService->cleanUp($this);
        parent::tearDown();
    }
}
