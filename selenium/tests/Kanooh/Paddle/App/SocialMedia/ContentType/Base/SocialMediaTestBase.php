<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\SocialMedia\ContentType\Base\SocialMediaTestBase.
 */

namespace Kanooh\Paddle\App\SocialMedia\ContentType\Base;

use Kanooh\Paddle\Apps\SocialMedia;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleSocialMedia\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminNodeViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class SocialMediaTestBase.
 */
abstract class SocialMediaTestBase extends WebDriverTestCase
{
    /**
     * @var AdminNodeViewPage
     */
    protected $adminViewPage;

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
     * @var ConfigurePage
     */
    protected $configurationPage;

    /**
     * @var FrontEndViewPage
     */
    protected $frontEndViewPage;

    /**
     * @var EditPage
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
    abstract protected function setupNode($title = null);

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->adminViewPage = new AdminNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->configurationPage = new ConfigurePage($this);
        $this->frontEndViewPage = new FrontEndViewPage($this);
        $this->nodeEditPage = new EditPage($this);

        // Bootstrap Drupal.
        $drupal = new DrupalService();
        $drupal->bootstrap($this);

        // Log in as Chief Editor.
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new SocialMedia);
    }

    /**
     * Tests the presence of the widget for each content type.
     *
     * @group socialMedia
     */
    public function testSocialMediaWidget()
    {
        // Create a node.
        $nid = $this->setupNode();
        $node = node_load($nid);

        // Go to the configuration page and enable the checkbox for this content
        // type.
        $this->configurationPage->go();
        $this->configurationPage->configureForm->getContentTypeCheckboxByName($node->type)->check();
        $this->configurationPage->configureForm->getSocialCheckboxByName('facebook')->check();
        $this->configurationPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The configuration options have been saved.');

        // Go to the front-end page of the node.
        $this->frontEndViewPage->go($nid);

        // Check that the share buttons are there.
        try {
            $this->frontEndViewPage->shareWidget;
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            $this->fail("The share widget is not shown for {$node->type}.");
        }

        // Disable the widget for this content type now.
        $this->configurationPage->go();
        $this->configurationPage->configureForm->getContentTypeCheckboxByName($node->type)->uncheck();
        $this->configurationPage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('The configuration options have been saved.');

        // Go to the front-end page of the node.
        $this->frontEndViewPage->go($nid);

        // Check that the share buttons are not there.
        try {
            $this->frontEndViewPage->shareWidget;
            $this->fail("The share widget should not be shown for {$node->type}.");
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // Everything is fine.
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
