<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\CustomCSS\ConfigurationTest.
 */

namespace Kanooh\Paddle\App\CustomCSS;

use Kanooh\Paddle\Apps\CustomCSS;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCustomCss\CrudPage\ContextAddPage;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCustomCss\CrudPage\ContextEditPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerAddPage\ThemerAddPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerEditPage\ThemerEditPage;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Pages\Element\CustomCss\ContextTableRow;
use Kanooh\Paddle\Pages\Element\CustomCss\DeleteModal;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminNodeViewPage;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\HttpRequest\HttpRequest;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCustomCss\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndView;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\TaxonomyService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs configuration tests on the Custom CSS paddlet.
 * @package Kanooh\Paddle\App\CustomCSS
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ConfigurationTest extends WebDriverTestCase
{
    /**
     * @var AdminNodeViewPage
     */
    protected $adminNodeViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var CleanUpService
     */
    protected $cleanUpService;

    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var ContextAddPage
     */
    protected $contextAddPage;

    /**
     * @var ContextEditPage
     */
    protected $contextEditPage;

    /**
     * @var FrontEndView
     */
    protected $frontendNodeViewPage;

    /**
     * @var FrontPage
     */
    protected $frontPage;

    /**
     * @var TaxonomyService
     */
    protected $taxonomyService;

    /**
     * @var ThemerAddPage
     */
    protected $themerAddPage;

    /**
     * @var ThemerEditPage
     */
    protected $themerEditPage;

    /**
     * @var ThemerOverviewPage
     */
    protected $themerOverviewPage;

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
        $this->adminNodeViewPage = new AdminNodeViewPage($this);
        $this->alphanumericDataProvider = new AlphanumericTestDataProvider();
        $this->cleanUpService = new CleanUpService($this);
        $this->configurePage = new ConfigurePage($this);
        $this->contextAddPage = new ContextAddPage($this);
        $this->contextEditPage = new ContextEditPage($this);
        $this->frontendNodeViewPage = new FrontEndView($this);
        $this->frontPage = new FrontPage($this);
        $this->taxonomyService = new TaxonomyService($this);
        $this->themerAddPage = new ThemerAddPage($this);
        $this->themerEditPage = new ThemerEditPage($this);
        $this->themerOverviewPage = new ThemerOverviewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new CustomCSS());

        $this->userSessionService->login('SiteManager');
    }

    /**
     * Tests the configuring aof the Custom CSS files in a Paddle theme and its
     * inclusion on the front-end.
     */
    public function testCustomCssConfiguration()
    {
        // Create new theme.
        $this->themerOverviewPage->go();

        $this->themerOverviewPage->contextualToolbar->buttonCreateTheme->click();
        $this->themerAddPage->checkArrival();

        // Create a new theme.
        $human_theme_name = $this->alphanumericDataProvider->getValidValue();
        $this->themerAddPage->name->clear();
        $this->themerAddPage->name->value($human_theme_name);
        $this->themerAddPage->baseTheme->selectOptionByValue('vo_standard');
        $this->themerAddPage->buttonSubmit->click();
        $this->themerEditPage->checkArrival();

        // Upload the custom css file.
        $this->themerEditPage->customCss->header->click();
        $location = dirname(__FILE__) . '/../../assets/custom.css';
        $file_path = $this->file($location);
        $this->themerEditPage->customCss->cssFile->chooseFile($file_path);
        $this->themerEditPage->customCss->cssFile->uploadButton->click();
        $this->themerEditPage->customCss->cssFile->waitUntilFileUploaded();

        // Save the theme.
        $theme_name = $this->themerEditPage->getThemeName();
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();

        // Preview the theme and make sure that the css was applied.
        $this->themerOverviewPage->theme($theme_name)->preview->click();
        $this->frontPage->checkArrival();
        $this->assertFalse($this->frontPage->pageHeader->displayed());

        // Edit the theme, make sure that the link to the CSS file is displayed.
        $this->frontPage->previewToolbar->closeButton->click();
        $this->themerEditPage->checkArrival();
        $this->themerEditPage->customCss->header->click();
        $url = $this->themerEditPage->customCss->cssFile->getFileLink()->attribute('href');

        // Make a request to get the CSS file, to verify its contents.
        $request = new HttpRequest($this);
        $request->setMethod(HttpRequest::GET);
        $request->setUrl($url);
        $response = $request->send();
        $this->assertEquals(200, $response->status);
        $this->assertNotEmpty($response->responseText);
        $this->assertContains(file_get_contents($location), $response->responseText);

        // Change the file to make sure updating the file will update the CSS on
        // the front-end. This is a regression check for https://one-agency.atlassian.net/browse/KANWEBS-3701.
        $test_data = array(
            array('filepath' => 'custom_css/custom.css', 'header_present' => true, 'title_present' => false),
            array('filepath' => 'custom.css', 'header_present' => false, 'title_present' => true),
        );

        foreach ($test_data as $data) {
            $this->themerEditPage->customCss->cssFile->removeButton->click();
            $this->themerEditPage->customCss->cssFile->waitUntilFileRemoved();
            $location = dirname(__FILE__) . '/../../assets/' . $data['filepath'];
            $file_path = $this->file($location);
            $this->themerEditPage->customCss->cssFile->chooseFile($file_path);
            $this->themerEditPage->customCss->cssFile->uploadButton->click();
            $this->themerEditPage->customCss->cssFile->waitUntilFileUploaded();
            $this->themerEditPage->buttonSubmit->click();
            $this->themerOverviewPage->checkArrival();
            $this->themerOverviewPage->theme($theme_name)->preview->click();
            $this->frontPage->checkArrival();
            $this->assertEquals($data['header_present'], $this->frontPage->pageHeader->displayed());
            $this->assertEquals($data['title_present'], $this->frontPage->pageTitle->displayed());

            $this->frontPage->previewToolbar->closeButton->click();
            $this->themerEditPage->checkArrival();
            $this->themerEditPage->customCss->header->click();
        }

        // Now remove the file and make sure the CSS is no longer applied.
        $this->themerEditPage->customCss->cssFile->removeButton->click();
        $this->themerEditPage->customCss->cssFile->waitUntilFileRemoved();
        $this->themerEditPage->buttonSubmit->click();
        $this->themerOverviewPage->checkArrival();
        $this->themerOverviewPage->theme($theme_name)->preview->click();
        $this->frontPage->checkArrival();
        $this->assertTrue($this->frontPage->pageHeader->displayed());
        $this->assertTrue($this->frontPage->pageTitle->displayed());
    }


    /**
     * Tests the contexts.
     */
    public function testCustomCssContextConfiguration()
    {
        // Clean up the contexts.
        $this->cleanUpService->deleteContexts();

        $vid = TaxonomyService::GENERAL_TAGS_VOCABULARY_ID;
        $term_title = strtolower($this->alphanumericDataProvider->getValidValue());
        $term_id = $this->taxonomyService->createTerm($vid, $term_title, 0);

        // Create one basic page for each level.
        $node_title = $this->alphanumericDataProvider->getValidValue();
        $node_nid = $this->createNodeForTerms($term_id, $node_title);

        $context_name = $this->alphanumericDataProvider->getValidValue();
        $class = strtolower($this->alphanumericDataProvider->getValidValue());

        // Go to the configuration page and fill it in.
        $this->configurePage->go();
        $this->assertTextPresent('No contexts have been created yet.');
        $this->configurePage->contextualToolbar->buttonNewContext->click();
        $this->contextAddPage->checkArrival();
        $this->contextAddPage->form->name->fill(strtolower($context_name));
        $this->contextAddPage->form->conditions->selectOptionByValue('node_taxonomy');
        $this->contextAddPage->form->reactions->selectOptionByValue('theme_html');
        $this->contextAddPage->form->taxonomy->selectOptionByLabel($term_title);
        $this->contextAddPage->form->class->fill($class);
        $this->contextAddPage->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();
        $this->assertTextPresent(strtolower($context_name) . ' has been created.');
        
        $rows = $this->configurePage->contextTable->rows;
        /** @var ContextTableRow $row */
        $row = reset($rows);
        $this->assertEquals(strtolower($context_name), $row->name);

        $row->linkEdit->click();
        $this->contextEditPage->checkArrival();
        $this->assertEquals(strtolower($context_name), $this->contextEditPage->form->name->getContent());
        $this->contextEditPage->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();

        // go to the font page of this node and make sure the class has been added.
        $this->frontendNodeViewPage->go($node_nid);
        $this->byCssSelector('body.' . $class);

        $this->configurePage->go();
        $rows = $this->configurePage->contextTable->rows;
        /** @var ContextTableRow $row */
        $row = reset($rows);
        $row->linkDelete->click();
        $modal = new DeleteModal($this);
        $modal->waitUntilOpened();
        $this->assertTextPresent('Are you sure you want to delete the context ' . strtolower($context_name) . '?');
        $modal->form->deleteButton->click();
        $modal->waitUntilClosed();
        $this->configurePage->checkArrival();
        $this->assertTextPresent('Context deleted');

        // go to the font page of this node and make sure the class has been deleted.
        $this->frontendNodeViewPage->go($node_nid);
        try {
            $this->byCssSelector('body.' . $class);
            $this->fail('The class set by the context, should be removed.');
        } catch (\Exception $e) {
            // Do nothing.
        }
    }


    /**
     * Create a new node for the specified term id.
     *
     * @param array|string $tids
     *   The taxonomy term ids to tag the node with.
     * @param string|null $title
     *   The title of the node. Leave empty to generate a random one.
     * @param null|callable $node_create_callback
     *   A callback to use for the node creation instead of the basic page default.
     *
     * @return int
     *   The nid of the node created.
     */
    protected function createNodeForTerms(
        $tids,
        $title = null,
        $node_create_callback = null
    ) {
        // Convert single value tids into array.
        if (!is_array($tids)) {
            $tids = array($tids);
        }
        $nid = $this->contentCreationService->createBasicPage();
        $node = node_load($nid);

        /* @ var \EntityMetadataWrapper $wrapper */
        $wrapper = entity_metadata_wrapper('node', $node);
        $wrapper->field_paddle_general_tags->set($tids);
        $wrapper->save();

        // Publish the node.
        $this->publishPage($nid);

        return $nid;
    }

    /**
     * Helper method to publish a node.
     *
     * @param int $nid
     *   The id of the node we want to publish.
     */
    protected function publishPage($nid)
    {
        $this->adminNodeViewPage->go($nid);
        $this->adminNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->adminNodeViewPage->checkArrival();
    }
}
