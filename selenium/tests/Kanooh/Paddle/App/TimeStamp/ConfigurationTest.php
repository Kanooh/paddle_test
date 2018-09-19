<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\TimeStamp\ConfigurationTest.
 */

namespace Kanooh\Paddle\App\TimeStamp;

use Kanooh\Paddle\Apps\TimeStamp;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleTimeStamp\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as FrontEndViewPage;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\WebDriver\WebDriverTestCase;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;

/**
 * Performs configuration tests on the Timestamp paddlet.
 *
 * @package Kanooh\Paddle\App\Timestamp
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ConfigurationTest extends WebDriverTestCase
{
    /**
     * @var ViewPage
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
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

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
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Prepare some variables for later use.
        $this->adminViewPage = new ViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->configurePage = new ConfigurePage($this);
        $this->frontEndViewPage = new FrontEndViewPage($this);
        $this->nodeEditPage = new EditPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as a site manager.
        $this->userSessionService->login('SiteManager');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new TimeStamp);
    }

    /**
     * Tests if that when you enable/disable the Timestamp checkbox on the
     * configuration page that the stars appear/disappear
     *
     * @group Timestamp
     */
    public function testEnableDisableConfiguration()
    {
        // Create a node.
        $nid = $this->contentCreationService->createBasicPage();

        // Go to the configuration page and enable the checkbox for this content type.
        $this->configurePage->go();
        $this->assertTextPresent('Basic page');

        $this->configurePage->configureForm->typeBasicPage->check();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');

        // Reload the page.
        $this->configurePage->go();

        // Make sure the correct checkbox is checked.
        $this->assertTrue($this->configurePage->configureForm->typeBasicPage->isChecked());

        // Verify that the checkbox is visible and selected.
        $this->nodeEditPage->go($nid);
        $this->assertTrue($this->nodeEditPage->generateTimestamp->isDisplayed());
        $this->assertTrue($this->nodeEditPage->generateTimestamp->isChecked());

        // Save the page to give the automatically created timestamp.
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();

        // Go back to the node edit page and make sure the time fields are still empty.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->generateTimestamp->uncheck();

        // Verify that the timestamp div is present.
        $xpath = '//div[contains(@class, "field-name-field-paddle-timestamp")]';
        $this->waitUntilElementIsPresent($xpath);
        $this->assertEmpty($this->nodeEditPage->timestampDate->getContent());
        $this->assertEmpty($this->nodeEditPage->timestampHour->getContent());

        // Check the timestamp checkbox again.
        $this->nodeEditPage->generateTimestamp->check();

        // Save the page and publish it to automatically create timestamp.
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();
        $this->adminViewPage->contextualToolbar->buttonPublish->click();

        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->generateTimestamp->uncheck();
        // The timestamp should now be filled.
        $xpath = '//div[contains(@class, "field-name-field-paddle-timestamp")]';
        $this->waitUntilElementIsPresent($xpath);
        $this->assertNotEmpty($this->nodeEditPage->timestampDate->getContent());
        $this->assertNotEmpty($this->nodeEditPage->timestampHour->getContent());

        // Lets change that value to a custom one.
        $tomorrow = strtotime('tomorrow');
        $tomorrow_date = date('d/m/Y', $tomorrow);
        $this->nodeEditPage->timestampDate->fill($tomorrow_date);
        $this->nodeEditPage->timestampHour->fill('09:10');

        // Save the page to set the timestamp.
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();
        $this->nodeEditPage->go($nid);

        // Now make sure the date is not the default value anymore.
        $this->assertEquals($tomorrow_date, $this->nodeEditPage->timestampDate->getContent());
        $this->assertEquals('09:10', $this->nodeEditPage->timestampHour->getContent());
    }

    /**
     * Tests if the already created nodes will get a default timestamp after enabling the
     * paddlet, only published nodes get a default value that is = to the last publishing date.
     *
     * @group Timestamp
     */
    public function testExistingNodesUpdatePath()
    {
        // We start by disabling the paddlet.
        $this->appService->disableApp(new Timestamp);
        drupal_cron_run();
        // Create a node.
        $nid = $this->contentCreationService->createBasicPage();

        // Verify that the checkbox not available yet.
        $this->nodeEditPage->go($nid);
        $this->assertTextNotPresent('Generate timestamp automatically');
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();
        $this->adminViewPage->contextualToolbar->buttonPublish->click();

        // Enable the paddelt back.
        $this->appService->enableApp(new Timestamp);
        // Go to the configuration page and enable the checkbox for this content type.
        $this->configurePage->go();
        $this->assertTextPresent('Basic page');
        $this->configurePage->configureForm->typeBasicPage->check();
        $this->configurePage->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Your configuration has been saved.');

        // Verify that there is a timestamp value now.
        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->generateTimestamp->uncheck();

        // Verify that the timestamp div is present.
        $xpath = '//div[contains(@class, "field-name-field-paddle-timestamp")]';
        $this->waitUntilElementIsPresent($xpath);
        $this->assertNotEmpty($this->nodeEditPage->timestampDate->getContent());
        $this->assertNotEmpty($this->nodeEditPage->timestampHour->getContent());
    }
}
