<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\Base\PaneLinkTestBase.
 */

namespace Kanooh\Paddle\Core\Pane\Base;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ReferencePage\ReferencePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage as NodeEditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Element\Pane\Pane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\PanelsContentType;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

/**
 * Base class for the Pane link tests.
 */
abstract class PaneLinkTestBase extends WebDriverTestCase
{

    /**
     * @var AdministrativeNodeViewPage
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
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * @var NodeEditPage
     */
    protected $nodeEditPage;

    /**
     * @var ReferencePage
     */
    protected $referencePage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * @var ViewPage
     */
    protected $viewPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->layoutPage = new LayoutPage($this);
        $this->nodeEditPage = new NodeEditPage($this);
        $this->referencePage = new ReferencePage($this);
        $this->viewPage = new ViewPage($this);

        $this->userSessionService = new UserSessionService($this);
        $this->appService = new AppService($this, $this->userSessionService);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Go to the login page and log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Creates an instance of the pane content type needed for the test.
     *
     * @return PanelsContentType
     *   A content type instance for the pane we need to test.
     */
    abstract protected function getPaneContentTypeInstance();

    /**
     * Run additional setup code needed for the test.
     *
     * Operation like atom creation or paddlet configuration goes here.
     * Everything that needs a page to be loaded basically, as that is not
     * possible anymore inside the pane configuration callback.
     */
    protected function additionalTestSetUp()
    {
        // By default, no additional setup is needed.
    }

    /**
     * Callback to configure the pane content type.
     *
     * @param PanelsContentType $content_type
     *   The pane content type that has been added.
     * @param array $references
     *   The id of the entity that has to be referenced.
     */
    abstract protected function configurePaneContentType($content_type, $references);

    /**
     * Creates entities that will be referenced in the test.
     *
     * By default creates a basic page. Can be overridden to create other
     * entities.
     *
     * @return array
     *   An array with the entity type and ids.
     */
    protected function setUpEntities()
    {
        $nid = $this->contentCreationService->createBasicPage();

        return array('node' => array($nid));
    }

    /**
     * Tests if the pane URL works if accessed from another node.
     *
     * @group panes
     * @group paneLink
     */
    public function testLinkInAnotherNode()
    {
        // Run any additional setup needed before executing test code.
        $this->additionalTestSetUp();

        global $base_url;

        // Create the entity.
        $entities = $this->setUpEntities();

        // Create a basic page to hold a pane.
        $nid = $this->contentCreationService->createBasicPage();

        // Add a pane to the page.
        $pane = $this->addPane($nid, $entities);
        $uuid = $pane->getUuid();

        // Define the URL as it should be.
        $expected_url = url('node/' . $nid, array('absolute' => true, 'alias' => true, 'fragment' => $uuid));

        // Retrieve the URL of the link from the pane.
        $url = $this->retrievePaneURL($pane, $nid);

        // Assert that the URL is built correctly.
        $this->assertEquals($expected_url, $url);

        // Create a second basic page and paste the link.
        $new_page_id = $this->contentCreationService->createBasicPage();

        $this->nodeEditPage->go($new_page_id);
        $this->nodeEditPage->body->waitUntilReady();
        $this->nodeEditPage->body->buttonLink->click();

        $modal = $this->nodeEditPage->body->modalLink;

        $modal->linkInfoForm->linkType->selectOptionByLabel('URL');
        $modal->linkInfoForm->linkType->waitUntilSelectedValueEquals('url');
        $modal->linkInfoForm->url->fill($url);
        $this->keys(Keys::ENTER);

        // Save the page.
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Go to the front end view and assert that the link is found here.
        $this->viewPage->go($new_page_id);
        $xpath = '//a[@href="' . $url . '"]';
        $url_element = $this->waitUntilElementIsDisplayed($xpath);

        // Click on the link.
        $url_element->click();
        $this->viewPage->checkArrival();

        // Assert that you are on the correct URL.
        $this->assertEquals($url, $base_url . $this->path());
    }

    /**
     * Tests if the internal link of the pane creates a node reference.
     *
     * @group linkChecker
     * @group panes
     * @group paneLink
     */
    public function testLinkChecker()
    {
        // Run any additional setup needed before executing test code.
        $this->additionalTestSetUp();

        global $base_url;

        // Create the entity that is going to be referenced.
        $entities = $this->setUpEntities();

        // Create a basic page to hold a pane.
        $referenced_title = $this->alphanumericTestDataProvider->getValidValue();
        $referenced_nid = $this->contentCreationService->createBasicPage($referenced_title);

        // Add a pane to the page.
        $pane = $this->addPane($referenced_nid, $entities);

        // Retrieve the URL of the link from the pane.
        $url = $this->retrievePaneURL($pane, $referenced_nid);

        // Create a second basic page and paste the link.
        $new_title = $this->alphanumericTestDataProvider->getValidValue();
        $new_page_id = $this->contentCreationService->createBasicPage($new_title);

        $this->nodeEditPage->go($new_page_id);
        $this->nodeEditPage->body->waitUntilReady();
        $this->nodeEditPage->body->buttonSource->click();

        $internal_path = str_replace($base_url, "", $url);
        $link = l($referenced_title, $internal_path);

        $this->nodeEditPage->body->setBodyText($link);
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Assert that on the Reference page of the referenced node
        // the new node title is shown.
        $this->referencePage->go($referenced_nid);
        $this->assertTextPresent("References to " . $referenced_title);
        $this->assertTextPresent($new_title);
    }

    /**
     * Add a pane to a node, runs the configuration of the pane and saves the page.
     *
     * @param int $nid
     *   The nid of the page where to add the pane.
     * @param array $references
     *   An array of entities that needs to be referenced.
     * @return Pane
     *   The created pane.
     */
    protected function addPane($nid, $references)
    {
        // Add a custom content pane in a region.
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();

        // Create an instance of the pane.
        $content_type = $this->getPaneContentTypeInstance();

        $test_case = $this;
        $callable = new SerializableClosure(
            function () use ($test_case, $content_type, $references) {
                $test_case->configurePaneContentType($content_type, $references);
            }
        );
        $pane = $region->addPane($content_type, $callable);

        // Save the layout page.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        return $pane;
    }

    /**
     * Retrieves the URL of the link on the pane edit modal form.
     *
     * @param Pane $pane
     *   The pane from which we need the URL.
     * @param $nid
     *   The ID of the node which the pane belongs to.
     *
     * @return string
     *   The URL of the link on the pane edit modal form.
     */
    protected function retrievePaneURL($pane, $nid)
    {
        // Go back to the layout page
        $this->layoutPage->go($nid);

        // Edit the pane and copy the URL.
        $pane->toolbar->buttonEdit->click();

        $pane->editPaneModal->waitUntilOpened();
        $url = $pane->editPaneModal->linkUrl->attribute('href');
        $pane->editPaneModal->submit();
        $this->layoutPage->checkArrival();
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        return $url;
    }
}
