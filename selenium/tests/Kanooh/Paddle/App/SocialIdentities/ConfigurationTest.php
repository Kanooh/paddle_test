<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\SocialIdentities\ConfigurationTest.
 */

namespace Kanooh\Paddle\App\SocialIdentities;

use Kanooh\Paddle\Apps\SocialIdentities;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleSocialIdentities\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\Admin\DashboardPage\DashboardPage;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage\MenuOverviewPage;
use Kanooh\Paddle\Pages\Element\SocialIdentities\SocialIdentitiesDeleteModal;
use Kanooh\Paddle\Pages\Element\SocialIdentities\SocialIdentityModal;
use Kanooh\Paddle\Pages\Element\SocialIdentities\SocialIdentitiesTableRow;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs configuration tests on the Social Identities paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ConfigurationTest extends WebDriverTestCase
{
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
     * The paddlet configuration page.
     *
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * The administation dashboard page.
     *
     * @var DashboardPage $dashboardPage
     */
    protected $dashboardPage;

    /**
     * The menu overview page.
     *
     * @var MenuOverviewPage
     */
    protected $menuOverviewPage;

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
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->configurePage = new ConfigurePage($this);
        $this->dashboardPage = new DashboardPage($this);
        $this->menuOverviewPage = new MenuOverviewPage($this);
        $this->userSessionService = new UserSessionService($this);

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new SocialIdentities);

        // Log in as a site manager.
        $this->userSessionService->login('SiteManager');
    }

    /**
     * Tests the add/edit/delete functionality for the social identities.
     */
    public function testAddEditDeleteSocialIdentity()
    {
        // Go to the configuration page and get a list of existing social
        // identities (if any).
        $this->configurePage->go();
        if ($this->configurePage->socialIdentitiesTablePresent()) {
            $social_identities = $this->configurePage->socialIdentitiesTable->rows;
        } else {
            $social_identities = array();
        }

        // Click the button to create a new social identity.
        $this->configurePage->contextualToolbar->buttonCreateIdentity->click();
        $modal = new SocialIdentityModal($this);
        $modal->waitUntilOpened();

        // Click the save button. The form should show validation errors.
        $modal->form->saveButton->click();
        $this->waitUntilTextIsPresent('Identity name field is required.');

        // Fill in the required fields and submit again.
        $name = $this->alphanumericTestDataProvider->getValidValue();
        $modal->form->name->fill($name);
        $modal->form->saveButton->click();
        $modal->waitUntilClosed();
        $this->configurePage->checkArrival();

        // Get a new list of social identities. Compare the count of the two and
        // get the one we just created.
        $updated_social_identities = $this->configurePage->socialIdentitiesTable->rows;
        $this->assertCount(count($social_identities) + 1, $updated_social_identities);

        /** @var SocialIdentitiesTableRow $social_identity */
        $social_identity = end($updated_social_identities);

        // Make sure the name in the list is the same as the one entered.
        $this->assertEquals($name, $social_identity->name);

        // Click the edit link and enter new values.
        $social_identity->linkEdit->click();
        $modal = new SocialIdentityModal($this);
        $modal->waitUntilOpened();

        $this->assertEquals($name, $modal->form->name->getContent());

        $new_name = $this->alphanumericTestDataProvider->getValidValue();
        $modal->form->name->fill($new_name);
        $modal->form->saveButton->click();
        $modal->waitUntilClosed();
        $this->configurePage->checkArrival();

        // Get a new list of social identities. Make sure the count is the as
        // before the edit.
        $updated_social_identities = $this->configurePage->socialIdentitiesTable->rows;
        $this->assertCount(count($social_identities) + 1, $updated_social_identities);
        $social_identity = end($updated_social_identities);

        // Make sure the name was updated in the list.
        $this->assertEquals($new_name, $social_identity->name);

        // Click the delete button, but cancel the confirmation.
        $social_identity->linkDelete->click();
        $modal = new SocialIdentitiesDeleteModal($this);
        $modal->waitUntilOpened();
        $modal->buttonCancel->click();
        $modal->waitUntilClosed();

        // Make sure the social identity count is the same.
        $previous_count = count($updated_social_identities);
        $updated_social_identities = $this->configurePage->socialIdentitiesTable->rows;
        $social_identity = end($updated_social_identities);
        $this->assertCount($previous_count, $updated_social_identities);

        // Click the delete button again, but actually delete the social
        // identity.
        $social_identity->linkDelete->click();
        $modal = new SocialIdentitiesDeleteModal($this);
        $modal->waitUntilOpened();
        $modal->buttonConfirm->click();
        $modal->waitUntilClosed();
        $this->configurePage->checkArrival();

        // Make sure the social identity count is reduced by one.
        $updated_social_identities = ($this->configurePage->socialIdentitiesTablePresent()) ? $this->configurePage->socialIdentitiesTable->rows : array();
        $this->assertCount($previous_count - 1, $updated_social_identities);

        // Delete all other social identities (if any) and make sure the
        // placeholder text appears. Don't store the rows in a variable, as they
        // will be stale once a social identity has been deleted and the rows
        // have been refreshed.
        while ($this->configurePage->socialIdentitiesTablePresent()) {
            $social_identity = $this->configurePage->socialIdentitiesTable->rows[0];
            $this->deleteSocialIdentity($social_identity);
        }
        $this->assertTextPresent('No identities have been created yet.');
    }

    /**
     * Tests the add/edit/delete functionality for the social identity urls.
     */
    public function testAddEditDeleteSocialIdentityURLs()
    {
        // Go to the configuration page.
        $this->configurePage->go();

        // Click the button to create a new social identity.
        $this->configurePage->contextualToolbar->buttonCreateIdentity->click();
        $modal = new SocialIdentityModal($this);
        $modal->waitUntilOpened();

        // Fill in the required fields and submit.
        $name = $this->alphanumericTestDataProvider->getValidValue();
        $modal->form->name->fill($name);

        // Verify the url field is present.
        /** @var SocialIdentityTableRow $first_row */
        $first_row = $modal->form->table->getRowByPosition(0);
        $this->assertTrue($first_row->url->isDisplayed());
        // Add an entry to the url field.
        $first_url = 'http://www.google.be';
        $first_row->url->fill($first_url);
        $first_title = 'Google';
        $first_row->title->fill($first_title);

        // Add an extra url/title pair.
        $second_url = 'http://www.sporza.be';
        $second_title = 'Sporza';
        /** @var SocialIdentityTableRow $second_row */
        $second_row = $modal->form->addNewUrlField();
        $second_row->url->fill($second_url);
        $second_row->title->fill($second_title);

        $modal->form->saveButton->click();
        $modal->waitUntilClosed();
        $this->configurePage->checkArrival();

        // Edit the identity and verify the values for the url are there.
        $updated_social_identities = $this->configurePage->socialIdentitiesTable->rows;
        /** @var SocialIdentitiesTableRow $social_identity */
        $social_identity = end($updated_social_identities);
        $social_identity->linkEdit->click();
        $modal = new SocialIdentityModal($this);
        $modal->waitUntilOpened();

        $first_row = $modal->form->table->getRowByPosition(0);
        $second_row = $modal->form->table->getRowByPosition(1);
        $this->assertEquals($first_url, $first_row->url->getContent());
        $this->assertEquals($first_title, $first_row->title->getContent());
        $this->assertEquals($second_url, $second_row->url->getContent());
        $this->assertEquals($second_title, $second_row->title->getContent());

        // Clear the last added url field and verify it has been deleted.
        $second_row->url->clear();
        $second_row->title->clear();
        $modal->form->saveButton->click();
        $modal->waitUntilClosed();
        $this->configurePage->checkArrival();

        $updated_social_identities = $this->configurePage->socialIdentitiesTable->rows;
        /** @var SocialIdentitiesTableRow $social_identity */
        $social_identity = end($updated_social_identities);
        $social_identity->linkEdit->click();
        $modal = new SocialIdentityModal($this);
        $modal->waitUntilOpened();
        $second_row = $modal->form->table->getRowByPosition(1);
        $this->assertEquals('', $second_row->url->getContent());
        $this->assertEquals('', $second_row->title->getContent());
        $modal->close();
        $this->configurePage->checkArrival();
    }

    /**
     * Deletes a given social identity.
     *
     * @param \Kanooh\Paddle\Pages\Element\SocialIdentities\SocialIdentitiesTableRow $social_identity
     *   The social identity (row) to delete.
     */
    protected function deleteSocialIdentity($social_identity)
    {
        $social_identity->linkDelete->click();
        $modal = new SocialIdentitiesDeleteModal($this);
        $modal->waitUntilOpened();
        $modal->buttonConfirm->click();
        $modal->waitUntilClosed();
        $this->configurePage->checkArrival();
    }

    /**
     * Data Provider for testDefaultAdminUIPath().
     */
    public function userDataProvider()
    {
        return array(
            array('Editor'),
            array('ChiefEditor'),
            array('SiteManager'),
        );
    }

    /**
     * Test that the default entity admin UI path is not accessible.
     *
     * Regression test for https://one-agency.atlassian.net/browse/KANWEBS-2809.
     *
     * @dataProvider userDataProvider
     *
     * @group regression
     */
    public function testDefaultAdminUIPath()
    {
        $url = 'admin/structure/paddle-social-identity';

        // First test direct access to the path.
        $this->url($url);
        $this->assertTextPresent('Access Denied');
        $this->assertTextPresent('You are not authorized to access this page.');

        // Then test that there is no menu link under "Structure" menu link.
        $this->dashboardPage->go();
        $this->dashboardPage->adminMenuLinks->linkStructure->click();
        $this->menuOverviewPage->checkArrival();
        $this->menuOverviewPage->adminMenuLinks->checkLinksNotPresent(array('PaddleSocialIdentities'));
    }
}
