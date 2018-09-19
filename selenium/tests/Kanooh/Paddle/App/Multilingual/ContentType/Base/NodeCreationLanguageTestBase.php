<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Multilingual\ContentType\Base\NodeCreationLanguageTestBase.
 */

namespace Kanooh\Paddle\App\Multilingual\ContentType\Base;

use Kanooh\Paddle\Apps\Multilingual;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage as AddContentPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\CreateNodeModal;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Listing\SearchPage\SearchPage as ContentManagerPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\MultilingualService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests for multilingual support of content types.
 *
 * @package Kanooh\Paddle\App\Multilingual\ContentType\Base
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
abstract class NodeCreationLanguageTestBase extends WebDriverTestCase
{
    /**
     * The 'Add content' page.
     *
     * @var AddContentPage
     */
    protected $addContentPage;

    /**
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * Test data provider for alphanumeric data.
     *
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
     * @var ContentManagerPage
     */
    protected $contentManagerPage;

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
        $this->addContentPage = new AddContentPage($this);
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->contentManagerPage = new ContentManagerPage($this);
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
     * Get the class name of the 'Add content' modal for the content type.
     *
     * @return string
     *   The class name of the modal for this content type.
     */
    public function getModalClassName()
    {
        return '\Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\CreateNodeModal';
    }

    /**
     * Get the machine name of the content type.
     *
     * @return string
     *   The machine name of the content type.
     */
    abstract protected function getContentTypeName();

    /**
     * Fills in the 'Add content' modal form with the required data for the content type.
     *
     * @param CreateNodeModal $modal
     *   The modal to fill-in.
     */
    public function fillInAddModalForm($modal)
    {
        /** @var CreateNodeModal $modal */
        $modal->title->fill($this->alphanumericTestDataProvider->getValidValue());
    }

    /**
     * Tests the language dropdown on the 'Add content' modal and the change of
     * the content language.
     *
     * @group testNodeCreationLanguage
     */
    public function testNodeCreationLanguage()
    {
        // First make sure we have only one language enabled to test that the
        // dropdown is not show when only one language is enabled. Also set
        // Dutch as default language.
        MultilingualService::disableAllNonDefaultLanguages($this);

        // Now check that in the 'Add content' modal there is no language dropdown.
        $this->addContentPage->go();

        // First open the modal. This is done differently only for the landing pages.
        $content_type = str_replace(' ', '', ucwords(str_replace('_', ' ', $this->getContentTypeName())));
        if ($content_type != 'LandingPage') {
            $this->addContentPage->links->{'link' . $content_type}->click();
        } else {
            // Create an Alfa page.
            $this->addContentPage->getLandingPageLayoutImage('paddle_2_col_3_9')->click();
        }

        $class_name = $this->getModalClassName();
        /** @var CreateNodeModal $modal */
        $modal = new $class_name($this);
        $modal->waitUntilOpened();
        $this->waitUntilTextIsPresent('information modal dialog');
        try {
            $modal->language;
            $this->fail('The language select should not be present.');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // Everything is fine.
        }
        $modal->close();

        // Enabled one more language - Bulgarian - to get the language dropdown in the modal.
        MultilingualService::enableLanguages($this, array('Bulgarian'));

        // Create a node and check that the language of the node is indeed changed.
        $this->addContentPage->go();
        if ($content_type != 'LandingPage') {
            $this->addContentPage->links->{'link' . $content_type}->click();
        } else {
            // Create an Alfa page.
            $this->addContentPage->getLandingPageLayoutImage('paddle_2_col_3_9')->click();
        }
        $modal->waitUntilOpened();
        $this->waitUntilTextIsPresent('information modal dialog');
        $this->fillInAddModalForm($modal);

        // Select Bulgarian.
        $modal->language->selectOptionByLabel('Bulgarian');
        $modal->submit();
        $this->administrativeNodeViewPage->checkArrival();

        // Remember the id of the created page so we can delete it at tearDown.
        $this->contentCreationService->rememberId($this->administrativeNodeViewPage->getNodeIDFromUrl());

        // Make sure the new node has Bulgarian as language.
        $this->administrativeNodeViewPage->nodeSummary->showAllMetadata();
        $page_language = $this->administrativeNodeViewPage->nodeSummary->getMetadataItem('structure', 'language');
        $this->assertEquals('Language:Bulgarian', $page_language->text());

        // When going to the content manager the language selected should be the default.
        $this->contentManagerPage->go();
        $this->assertEquals('nl', $this->contentManagerPage->languageSwitcher->getActiveLanguage());
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
