<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Multilingual\EnableDisableTest.
 */

namespace Kanooh\Paddle\App\Multilingual;

use Kanooh\Paddle\App\Base\EnableDisableTestBase;
use Kanooh\Paddle\Apps\Multilingual;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleMultilingual\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndNodeViewPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\HttpRequest\HttpRequest;
use Kanooh\Paddle\Utilities\MultilingualService;

/**
 * Class EnableDisableTest
 *
 * @package Kanooh\Paddle\App\Multilingual
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class EnableDisableTest extends EnableDisableTestBase
{

    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var FrontEndNodeViewPage
     */
    protected $frontEndNodeViewPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();
        $this->configurePage = new ConfigurePage($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->frontEndNodeViewPage = new FrontEndNodeViewPage($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getApp()
    {
        return new Multilingual;
    }

    /**
     * Tests whether the language configuration is restored after
     * disable/enable.
     */
    public function testRestoreLanguageConfiguration()
    {
        // Only Site managers can manage paddlet configuration.
        $this->userSessionService->switchUser('SiteManager');

        // Enable the Multilingual paddlet and go to the configuration page.
        $this->appService->enableApp($this->app);
        $this->configurePage->go();

        // Enable Czech and disable German.
        $this->configurePage->form->enableCzech->check();
        $this->configurePage->form->enableGerman->uncheck();
        // Set French as default language.
        $this->configurePage->form->defaultFrench->select();

        // Save the configuration.
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();

        // Create a Czech basic page.
        $nid = $this->contentCreationService->createBasicPage();
        $this->contentCreationService->changeNodeLanguage($nid, 'cs');

        // Check if you can go to the front-end page.
        $this->frontEndNodeViewPage->go($nid);

        // Disable the Multilingual app.
        $this->appService->disableApp($this->app);

        // try to go to the front-end page and assert a 404.
        $request = new HttpRequest($this);
        $request->setMethod(HttpRequest::GET);
        $request->setUrl("node/$nid");
        $response = $request->send();

        // Expect a 404.
        $this->assertEquals(404, $response->status);

        // Enable the Multilingual paddlet again.
        $this->appService->enableApp($this->app);

        // Assert the configuration did not change.
        $this->configurePage->go();
        $this->assertTrue($this->configurePage->form->enableCzech->isChecked());
        $this->assertFalse($this->configurePage->form->enableGerman->isChecked());
        $this->assertTrue($this->configurePage->form->defaultFrench->isSelected());

        // Assert that you can reach the Czech page again.
        $this->frontEndNodeViewPage->go($nid);

        // Set the default values back so future tests won't fail.
        MultilingualService::setPaddleTestDefaults($this);
    }
}
