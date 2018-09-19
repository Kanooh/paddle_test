<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentRegion\ContentRegionSettingsTest.
 */

namespace Kanooh\Paddle\Core\ContentRegion;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Pages\Admin\ContentManager\Entity\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Admin\Structure\ContentRegionPage\ContentRegionPage;
use Kanooh\Paddle\Pages\Admin\Structure\ContentRegionPage\ContentRegionUtility;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests for the configuration of content regions.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ContentRegionSettingsTest extends WebDriverTestCase
{

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * The content region settings.
     *
     * @var ContentRegionPage
     */
    protected $contentRegionPage;

    /**
     * The page that allows to edit the Panels display of a node.
     *
     * @var PanelsContentPage
     */
    protected $panelsContentPage;

    /**
     * The random data generation class.
     *
     * @var Random $random
     */
    protected $random;

    /**
     * Test content
     *
     * @var array
     */
    protected $testContent;

    /**
     * The utility class for common function for content regions.
     *
     * @var ContentRegionUtility
     */
    protected $contentRegionUtility;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Instantiate the Pages that will be visited in the test.
        $this->contentRegionPage = new ContentRegionPage($this);
        $this->panelsContentPage = new PanelsContentPage($this);
        $this->random = new Random();
        $this->contentRegionUtility = new ContentRegionUtility($this);

        // Set up test data.
        $this->testContent['all_pages']['right'] = $this->random->name(64);
        $this->testContent['all_pages']['bottom'] = $this->random->name(64);
        $this->testContent['basic_page']['right'] = $this->random->name(64);
        $this->testContent['basic_page']['bottom'] = $this->random->name(64);

        // Go to the login page and log in as chief editor.
        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Set default value back to default.
        $this->contentRegionPage = new ContentRegionPage($this);
        $this->contentRegionPage->go();
        $this->contentRegionPage->getOverride('basic_page')->checkbox->click();
        $this->contentRegionPage->contextualToolbar->buttonSave->click();
        $this->waitUntilElementIsPresent('//div[@id="messages"]');

        // Log out.
        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->logout();

        parent::tearDown();
    }

    /**
     * Tests the user interface of the content regions.
     *
     * @group panes
     */
    public function testContentRegionInterface()
    {
        // Go to the content region configuration page.
        $this->contentRegionPage->go();
        $this->contentRegionPage->links->checkLinks();
        $this->contentRegionPage->adminMenuLinks->checkLinks(array('Dashboard', 'Structure', 'Content', 'PaddleStore'));

        // Check if the checkbox for the basic pages is checked - if it is uncheck it.
        $this->contentRegionPage->getOverride('basic_page')->disable();
        // Save new setting.
        $this->contentRegionPage->contextualToolbar->buttonSave->click();
        $this->waitUntilElementIsPresent('//div[@id="messages"]');

        // Check if it unchecked.
        $this->contentRegionPage->checkArrival();
        $this->assertFalse($this->contentRegionPage->getOverride('basic_page')->checkbox->selected());

        // Add custom content panes to the global settings for All pages.
        $this->contentRegionUtility->addCustomContentPanes(
            $this->contentRegionPage->links->linkEditContentForAllPages,
            $this->testContent['all_pages']['right'],
            $this->testContent['all_pages']['bottom']
        );

        // Back on the configuration page. Wait for page load and initialize.
        $this->contentRegionPage->checkArrival();

        // Click the edit link again and ensure the panes are still there.
        $this->contentRegionPage->links->linkEditContentForAllPages->click();

        // We land on the Panels Content page.
        $this->panelsContentPage->checkArrival();
        $this->assertTextPresent($this->testContent['all_pages']['right']);
        $this->assertTextPresent($this->testContent['all_pages']['bottom']);

        // Click 'Save' to get back to the configuration page.
        $this->panelsContentPage->contextualToolbar->buttonSave->click();
        $this->contentRegionPage->checkArrival();

        // Override the setting for the basic pages.
        $this->contentRegionPage->getOverride('basic_page')->enable();
        // Save new setting.
        $this->contentRegionPage->contextualToolbar->buttonSave->click();
        $this->waitUntilElementIsPresent('//div[@id="messages"]');

        // Ensure it's saved.
        $this->contentRegionPage->checkArrival();
        $checkbox = $this->contentRegionPage->getOverride('basic_page')->checkbox;
        $this->assertTrue($checkbox->selected());

        // Add custom content panes to the global settings for All basic pages.
        $this->contentRegionUtility->addCustomContentPanes(
            $this->contentRegionPage->getOverride('basic_page')->editLink,
            $this->testContent['basic_page']['right'],
            $this->testContent['basic_page']['bottom']
        );

        // Back on the configuration page. Wait for page load and initialize.
        $this->contentRegionPage->checkArrival();

        // Click the edit link again and ensure the panes are still there.
        $this->contentRegionPage->getOverride('basic_page')->editLink->click();

        $this->panelsContentPage->checkArrival();
        $this->assertTextPresent($this->testContent['basic_page']['right']);
        $this->assertTextPresent($this->testContent['basic_page']['bottom']);

        // Go to the content region configuration page.
        $this->contentRegionPage->go();
        $this->contentRegionPage->links->checkLinks();
    }
}
