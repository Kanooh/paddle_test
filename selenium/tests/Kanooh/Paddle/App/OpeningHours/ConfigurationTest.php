<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OpeningHours\ConfigurationTest.
 */

namespace Kanooh\Paddle\App\OpeningHours;

use Kanooh\Paddle\Apps\OpeningHours;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleOpeningHours\AddEditPage\OpeningHourAddPage;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleOpeningHours\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class ConfigurationTest
 * @package Kanooh\Paddle\App\OpeningHours
 *
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
     * @var CleanUpService
     */
    protected $cleanUpService;

    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var OpeningHourAddPage
     */
    protected $openingHourAddPage;

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

        // Instantiate some classes to use in the test.
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->configurePage = new ConfigurePage($this);
        $this->openingHourAddPage = new OpeningHourAddPage($this);
        $this->userSessionService = new UserSessionService($this);

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new OpeningHours);
    }

    /**
     * Tests the Add functionality.
     */
    public function testAddOpeningHour()
    {
        $this->cleanUpService = new CleanUpService($this);
        $this->cleanUpService->deleteEntities('opening_hours_set');

        $this->configurePage->go();
        $this->assertTextPresent('No opening hours sets have been created yet.');

        $this->configurePage->contextualToolbar->buttonAdd->click();
        $this->openingHourAddPage->checkArrival();

        // Verify that the elements are required.
        $this->openingHourAddPage->form->contextualToolbar->buttonSave->click();
        $this->waitUntilTextIsPresent('Opening hours set title field is required.');

        // Create a new opening hour.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $this->openingHourAddPage->form->title->fill($title);
        $this->openingHourAddPage->form->contextualToolbar->buttonSave->click();
        $this->configurePage->checkArrival();
        $this->assertTextPresent('Opening hours set saved.');
        $this->waitUntilTextIsPresent($title);

        $this->assertTrue($this->configurePage->openingHoursTable->isPresent());
    }
}
