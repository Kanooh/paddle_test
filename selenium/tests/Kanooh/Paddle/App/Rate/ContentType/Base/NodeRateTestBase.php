<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Rate\ContentType\Base\NodeRateTestBase.
 */

namespace Kanooh\Paddle\App\Rate\ContentType\Base;

use Kanooh\Paddle\Apps\Rate;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleRate\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs tests for rate on content types.
 *
 * @package Kanooh\Paddle\App\Rate\ContentType\Base
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
abstract class NodeRateTestBase extends WebDriverTestCase
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
     * @var FrontEndViewPage
     */
    protected $frontEndViewPage;

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
        $this->frontEndViewPage = new FrontEndViewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);


        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);

        // Log in as Chief Editor.
        $this->userSessionService->login('ChiefEditor');
        $this->appService->enableApp(new Rate);
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
     * @group NodeRateTestBase
     * @group Rate
     */
    public function testDefaultSettingsAndConfiguration()
    {
        $type_name = $this->getContentTypeName();
        // Check some default settings now the paddlet is installed.
        $this->assertEquals(0, variable_get("paddle_rate_$type_name", 0));

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
        $this->assertEquals(1, $conf['paddle_rate_' . $type_name]);
    }

    /**
     * Tests if the score of the page has been saved after rating once.
     *
     * @group Rate
     */
    public function testRatingSaved()
    {
        $type_name = $this->getContentTypeName();
        $content_type = 'type' . str_replace(' ', '', ucwords(str_replace('_', ' ', $type_name)));

        $this->configurePage->go();
        $this->configurePage->configureForm->$content_type->check();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');

        // Create a node and enable rating.
        $nid = $this->setupNode();
        $this->contentCreationService->enableRating($nid);

        //Click on the third star to vote.
        $this->frontEndViewPage->go($nid);
        $element = $this->byCssSelector(".star-3 a");
        $element->click();
        sleep(1);

        // Check if the vote has been saved and there are three stars
        // highlighted.
        $xpath_highlighted_stars = '//div[contains(@class, "fivestar-widget")]/div[contains(@class, "on")]';
        $this->waitUntilElementIsPresent($xpath_highlighted_stars);
        $highlighted_stars = $this->elements($this->using('xpath')
            ->value($xpath_highlighted_stars));
        $this->assertCount(3, $highlighted_stars);
    }

    /**
     * Tests the Enable/Disable Rating Checkboxes per node.
     *
     * @group Rate
     */
    public function testEnableDisableRatingPerNode()
    {
        $type_name = $this->getContentTypeName();
        $content_type = 'type' . str_replace(' ', '', ucwords(str_replace('_', ' ', $type_name)));

        $this->configurePage->go();
        $this->configurePage->configureForm->$content_type->check();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');

        // Create a node.
        $nid = $this->setupNode();

        //Check if the stars are hidden by default after creating a node.
        $this->frontEndViewPage->go($nid);
        $xpath = '//div[contains(@class, "field-type-fivestar")]';
        $this->waitUntilElementIsNoLongerPresent($xpath);

        //Check if the checkbox is not checked on the edit page.
        $this->nodeEditPage->go($nid);
        $this->assertFalse($this->nodeEditPage->enableRatingCheckbox->isChecked());

        //Enable rating for this node.
        $this->contentCreationService->enableRating($nid);

        //Check if the stars are present now.
        $this->frontEndViewPage->go($nid);
        $xpath = '//div[contains(@class, "field-type-fivestar")]';
        $this->waitUntilElementIsPresent($xpath);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->contentCreationService->cleanUp($this);

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
