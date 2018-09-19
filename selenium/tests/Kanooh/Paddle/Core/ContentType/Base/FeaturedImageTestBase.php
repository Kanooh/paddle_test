<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\Base\FeaturedImageTestBase.
 */

namespace Kanooh\Paddle\Core\ContentType\Base;

use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminNodeViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage as NodeEditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndNodeViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the featured image.
 */
abstract class FeaturedImageTestBase extends WebDriverTestCase
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
     * @var FrontEndNodeViewPage
     */
    protected $frontEndNodeViewPage;

    /**
     * @var NodeEditPage
     */
    protected $nodeEditPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

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
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Instantiate some objects for later use.
        $this->adminNodeViewPage = new AdminNodeViewPage($this);
        $this->assetCreationService = new AssetCreationService($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->frontEndNodeViewPage = new FrontEndNodeViewPage($this);
        $this->nodeEditPage = new NodeEditPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as editor.
        $this->userSessionService->login('Editor');
    }

    /**
     * Tests the features image.
     *
     * @group featuredImage
     */
    public function testFeaturedImage()
    {
        // Create an image atom to test with.
        $atom = $this->assetCreationService->createImage();

        $nid = $this->setupNode();
        $this->nodeEditPage->go($nid);

        $this->nodeEditPage->featuredImage->selectAtom($atom['id']);
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        $this->nodeEditPage->go($nid);
        $this->assertEquals($atom['id'], $this->nodeEditPage->featuredImage->valueField->value());

        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        // Verify that the image is not shown in the front end.
        $this->frontEndNodeViewPage->go($nid);
        $node = node_load($nid);
        if (!in_array($node->type, array('organizational_unit', 'news_item', 'offer'))) {
            try {
                $this->byCssSelector('.field-name-field-paddle-featured-image');
                $this->fail('Unwanted featured image found.');
            } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
                // Do nothing, no image shown.
            }
        }
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
