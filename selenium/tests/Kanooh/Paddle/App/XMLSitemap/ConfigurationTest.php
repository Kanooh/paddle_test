<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\XMLSiteMap\ConfigurationTest.
 */

namespace Kanooh\Paddle\App\XMLSiteMap;

use Kanooh\Paddle\Apps\Multilingual;
use Kanooh\Paddle\Apps\XMLSiteMap;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleXMLSiteMap\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Element\XMLSiteMap\BaseUrlModal;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;

/**
 * Performs configuration tests on the XML Site Map paddlet.
 *
 * @package Kanooh\Paddle\App\XMLSiteMap
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ConfigurationTest extends WebDriverTestCase
{

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

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

        // Enable the app if it is not yet enabled.
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->configurePage = new ConfigurePage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new XMLSiteMap());
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // In case if Multilingual is enabled we need to disable the module.
        $app = new Multilingual;
        $this->appService->disableAppsByMachineNames(array($app->getModuleName()));

        // Log in as Site Manager.
        $this->userSessionService->login('SiteManager');
    }

    /**
     * Tests the configuration of the base URL.
     */
    public function testConfiguration()
    {
        // Open the configuration page and add a random base URL to the page.
        $random_base_url = 'http://' . $this->alphanumericTestDataProvider->getValidValue();
        $this->configurePage->go();
        $this->configurePage->contextualToolbar->buttonEditBaseUrl->click();
        $modal = new BaseUrlModal($this);
        $modal->waitUntilOpened();
        $modal->form->baseUrl->fill($random_base_url);
        $modal->form->saveButton->click();
        $this->waitUntilTextIsPresent('Your base URL has been successfully updated. Please wait a few minutes for the XML site map to be updated.');

        // Retrieve the default XML site map and check that the base URL has
        // been used to build the link.
        $row_the_boat_row = $this->configurePage->linksTable->getRowByLanguage('default');
        $this->assertEquals($random_base_url . '/sitemap.xml', $row_the_boat_row->link);

        // Enable the Multilingual paddlet.
        $this->appService->enableApp(new Multilingual());
        $this->configurePage->go();
        // Retrieve the Dutch table row.
        $row_the_boat_row_in_nederland = $this->configurePage->linksTable->getRowByLanguage('dutch');
        $this->assertEquals($random_base_url . '/nl/sitemap.xml', $row_the_boat_row_in_nederland->link);
    }
}
