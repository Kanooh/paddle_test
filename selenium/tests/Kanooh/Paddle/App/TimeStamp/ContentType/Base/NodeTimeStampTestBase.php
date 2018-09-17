<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\TimeStamp\ContentType\Base\NodeTimeStampTestBase.
 */

namespace Kanooh\Paddle\App\TimeStamp\ContentType\Base;

use Kanooh\Paddle\Apps\TimeStamp;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleTimeStamp\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests for timestamp on content types.
 *
 * @package Kanooh\Paddle\App\TimeStamp\ContentType\Base
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
abstract class NodeTimeStampTestBase extends WebDriverTestCase
{
    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var ViewPage
     */
    protected $adminViewPage;

    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var EditPage
     */
    protected $nodeEditPage;

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

        // Prepare some variables for later use.
        $this->adminViewPage = new ViewPage($this);
        $this->configurePage = new ConfigurePage($this);
        $this->nodeEditPage = new EditPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);


        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);

        // Log in as Chief Editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Get the machine name of the content type.
     *
     * @return string
     *   The machine name of the content type.
     */
    abstract protected function getContentTypeName();

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
     * Tests the saving of the paddlet's default settings and the configuration.
     *
     * @group TimeStamp
     */
    public function testAppDefaultSettingsAndConfiguration()
    {
        // Enable the TimeStamp paddlet.
        $this->appService->enableApp(new TimeStamp());

        $type_name = $this->getContentTypeName();
        // Check some default settings now the paddlet is installed.
        $this->assertEquals(0, variable_get("paddle_timestamp_$type_name", 0));

        // Now check the configuration page.
        $this->configurePage->go();
        $content_type = 'type' . str_replace(' ', '', ucwords(str_replace('_', ' ', $type_name)));
        $this->assertFalse($this->configurePage->configureForm->$content_type->isChecked());

        // Now check the content type settings.
        $this->configurePage->configureForm->$content_type->check();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');

        // Use the global $conf as variable_get() is not immediately updated.
        $conf = variable_initialize();
        $this->assertEquals(1, $conf['paddle_timestamp_' . $type_name]);
    }

    /**
     * Tests if the score of the page has been saved after rating once.
     *
     * @group TimeStamp
     */
    public function testNodeDefaultSettingsAndConfiguration()
    {
        // Make sure the paddlet is disabled.
        $app = new TimeStamp;
        $this->appService->disableAppsByMachineNames(array($app->getModuleName()));

        // Create a Node before the paddlet is enabled.
        $existing_nid = $this->setupNode();

        // Enable the TimeStamp paddlet.
        $this->appService->enableApp($app);

        // Enable timestamps for the current node type.
        $type_name = $this->getContentTypeName();
        $content_type = 'type' . str_replace(' ', '', ucwords(str_replace('_', ' ', $type_name)));

        $this->configurePage->go();
        $this->configurePage->configureForm->$content_type->check();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');

        // Browse to the edit page of the existing node.
        $this->nodeEditPage->go($existing_nid);

        // Verify that the checkbox is visible and selected.
        $this->assertTrue($this->nodeEditPage->generateTimestamp->isDisplayed());
        $this->assertTrue($this->nodeEditPage->generateTimestamp->isChecked());

        // Uncheck the checkbox and save the page.
        $this->nodeEditPage->generateTimestamp->uncheck();
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        // Go back and assert that the checkbox had been saved correctly.
        $this->nodeEditPage->go($existing_nid);
        $this->assertFalse($this->nodeEditPage->generateTimestamp->isChecked());

        // Save the page.
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        // Create a new page and assert the default value of the checkbox as well.
        $new_nid = $this->setupNode();
        $this->nodeEditPage->go($new_nid);

        $this->assertTrue($this->nodeEditPage->generateTimestamp->isDisplayed());
        $this->assertTrue($this->nodeEditPage->generateTimestamp->isChecked());

        // Save the page.
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Go to the configure page and disable the enabled types.
        $this->configurePage->go();
        $type_name = $this->getContentTypeName();
        $content_type = 'type' . str_replace(' ', '', ucwords(str_replace('_', ' ', $type_name)));
        $this->configurePage->configureForm->$content_type->uncheck();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');

        parent::tearDown();
    }
}
