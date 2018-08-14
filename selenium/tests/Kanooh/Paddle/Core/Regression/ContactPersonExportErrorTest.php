<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Regression\ContactPersonExportError.
 */

namespace Kanooh\Paddle\Core\Regression;

use Kanooh\Paddle\Apps\ContactPerson;
use Kanooh\Paddle\Apps\OrganizationalUnit;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleContactPerson\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleContactPerson\ConfigurePage\ConfigurePageOuEnabled;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\ContactPerson\ContactPersonEditPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 *
 * @see https://one-agency.atlassian.net/browse/KANWEBS-4844
 */
class ContactPersonExportErrorTest extends WebDriverTestCase
{
    /**
     * @var ViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var ConfigurePageOuEnabled
     */
    protected $configurePageOuEnabled;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var ContactPersonEditPage
     */
    protected $editContactPersonPage;

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

        // Create some instances to use later on.
        $this->administrativeNodeViewPage = new ViewPage($this);
        $this->assetCreationService = new AssetCreationService($this);
        $this->configurePage = new ConfigurePage($this);
        $this->editContactPersonPage = new ContactPersonEditPage($this);
        $this->configurePageOuEnabled = new ConfigurePageOuEnabled($this);

        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->login('ChiefEditor');

        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Enable the Contact Person app in case it is not enabled yet.
        $app_service = new AppService($this, $this->userSessionService);
        $app_service->enableApp(new ContactPerson);

        // Disable paddle_organizational_unit. Import view changes when paddle_OU is enabled.
        $app = new OrganizationalUnit;
        $app_service->disableAppsByMachineNames(array($app->getModuleName()));
    }

    /**
     * Tests that there is no error message when exporting contact person with
     * an atom image and the download link exists.
     *
     * @group contactPerson
     * @group regression
     */
    public function testContactPersonExports()
    {
        // Create a contact person with an image to test with.
        $atom = $this->assetCreationService->createImage();
        $nid = $this->contentCreationService->createContactPerson();
        $this->editContactPersonPage->go($nid);
        $this->editContactPersonPage->form->photo->selectAtom($atom['id']);
        $this->editContactPersonPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Now check the CSV export.
        foreach (array('exportCSV', 'exportXLS') as $button) {
            $this->configurePage->go();
            $this->configurePage->{$button}->click();
            $this->waitUntilTextIsPresent('Your export has been created.');
            $this->assertTextNotPresent('Export has encountered an error.');
        }

        // Enable paddle_organizational_unit.
        $app_service = new AppService($this, $this->userSessionService);
        $app_service->enableApp(new OrganizationalUnit);

        // Now check the CSV export.
        foreach (array('exportCSV', 'exportXLS') as $button) {
            $this->configurePageOuEnabled->go();
            $this->configurePageOuEnabled->{$button}->click();
            $this->waitUntilTextIsPresent('Your export has been created.');
            $this->assertTextNotPresent('Export has encountered an error.');
        }
    }
}
