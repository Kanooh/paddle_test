<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Cirro\CirroAdvancedSearchTest.
 */

namespace Kanooh\Paddle\App\Cirro;

use Kanooh\Paddle\Apps\Cirro;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Node\EditPage\AdvancedSearch\AdvancedSearchPage;
use Kanooh\Paddle\Pages\Node\EditPage\Cirro\CirroEditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\AdvancedSearch\AdvancedSearchViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalApi\DrupalSearchApiApi;
use Kanooh\Paddle\Utilities\TaxonomyService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class PublicationAdvancedSearchTest
 * @package Kanooh\Paddle\App\Publication
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class CirroAdvancedSearchTest extends WebDriverTestCase
{
    /**
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var AdvancedSearchPage
     */
    protected $advancedSearchEditPage;

    /**
     * @var AdvancedSearchViewPage
     */
    protected $advancedSearchFrontViewPage;

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
     * @var DrupalSearchApiApi
     */
    protected $drupalSearchApiApi;

    /**
     * @var CirroEditPage
     */
    protected $cirroEditPage;

    /**
     * @var TaxonomyService
     */
    protected $taxonomyService;

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
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->advancedSearchEditPage = new AdvancedSearchPage($this);
        $this->advancedSearchFrontViewPage = new AdvancedSearchViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->drupalSearchApiApi = new DrupalSearchApiApi($this);
        $this->cirroEditPage = new CirroEditPage($this);
        $this->taxonomyService = new TaxonomyService();

        // Go to the login page and log in as Chief Editor.
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Cirro);
    }

    /**
     * Tests the extra fields on the advanced search page.
     *
     * @group facets
     */
    public function testAdvancedSearchExtraFields()
    {
        // Create a CIRRO page.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $pub_nid = $this->contentCreationService->createCirroPage($title);

        // Create an advanced search page.
        $nid = $this->contentCreationService->createAdvancedSearchPage();

        $action_strategy = $this->alphanumericTestDataProvider->getValidValue();
        $policy_theme = $this->alphanumericTestDataProvider->getValidValue();
        $setting = $this->alphanumericTestDataProvider->getValidValue();
        $action_strategy_voc = taxonomy_vocabulary_machine_name_load('paddle_cirro_action_strategies');
        $policy_theme_voc = taxonomy_vocabulary_machine_name_load('paddle_cirro_policy_themes');
        $setting_voc = taxonomy_vocabulary_machine_name_load('paddle_cirro_settings');
        $action_strategy_tid = $this->taxonomyService->createTerm($action_strategy_voc->vid, $action_strategy);
        $policy_theme_tid = $this->taxonomyService->createTerm($policy_theme_voc->vid, $policy_theme);
        $setting_tid = $this->taxonomyService->createTerm($setting_voc->vid, $setting);

        // Go to the CIRRO edit page and add all three vocabulary terms.
        $this->cirroEditPage->go($pub_nid);

        $this->cirroEditPage->form->actionStrategies->fill($action_strategy);
        $this->clickOnceElementIsVisible($this->cirroEditPage->form->actionStrategiesAddButton);
        $this->cirroEditPage->form->policyThemes->fill($policy_theme);
        $this->clickOnceElementIsVisible($this->cirroEditPage->form->policyThemesAddButton);
        $this->cirroEditPage->form->settings->fill($setting);
        $this->clickOnceElementIsVisible($this->cirroEditPage->form->settingsAddButton);
        $this->cirroEditPage->contextualToolbar->buttonSave->click();

        // Publish the CIRRO page.
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Reindex the node index.
        // Index all the nodes and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Go to the advanced search edit page and check the new fields.
        $this->advancedSearchEditPage->go($nid);
        $this->assertFalse($this->advancedSearchEditPage->advancedSearchForm->filterActionStrategiesCheckbox->isChecked());
        $this->assertFalse($this->advancedSearchEditPage->advancedSearchForm->filterPolicyThemesCheckbox->isChecked());
        $this->assertFalse($this->advancedSearchEditPage->advancedSearchForm->filterSettingsCheckbox->isChecked());
        $this->advancedSearchEditPage->advancedSearchForm->filterActionStrategiesCheckbox->check();
        $this->advancedSearchEditPage->advancedSearchForm->filterPolicyThemesCheckbox->check();
        $this->advancedSearchEditPage->advancedSearchForm->filterSettingsCheckbox->check();

        $this->advancedSearchEditPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Go to the front end of the advanced search page and verify 2 new
        // panes with the correct content are shown.
        $this->advancedSearchFrontViewPage->go($nid);
        $this->assertNotEmpty($this->advancedSearchFrontViewPage->actionStrategiesFilterFacet->getInactiveLinkByValue($action_strategy_tid));
        $this->assertNotEmpty($this->advancedSearchFrontViewPage->policyThemesFilterFacet->getInactiveLinkByValue($policy_theme_tid));
        $this->assertNotEmpty($this->advancedSearchFrontViewPage->settingsFilterFacet->getInactiveLinkByValue($setting_tid));

        // Uncheck the checkboxes and verify that the panes are gone.
        $this->advancedSearchEditPage->go($nid);
        $this->assertTrue($this->advancedSearchEditPage->advancedSearchForm->filterActionStrategiesCheckbox->isChecked());
        $this->assertTrue($this->advancedSearchEditPage->advancedSearchForm->filterPolicyThemesCheckbox->isChecked());
        $this->assertTrue($this->advancedSearchEditPage->advancedSearchForm->filterSettingsCheckbox->isChecked());
        $this->advancedSearchEditPage->advancedSearchForm->filterActionStrategiesCheckbox->uncheck();
        $this->advancedSearchEditPage->advancedSearchForm->filterPolicyThemesCheckbox->uncheck();
        $this->advancedSearchEditPage->advancedSearchForm->filterSettingsCheckbox->uncheck();

        $this->advancedSearchEditPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        $this->advancedSearchFrontViewPage->go($nid);

        try {
            $this->advancedSearchFrontViewPage->actionStrategiesFilterFacet;
            $this->fail('The action strategies facet should not be shown.');
        } catch (\Exception $e) {
            // Do nothing.
        }

        try {
            $this->advancedSearchFrontViewPage->policyThemesFilterFacet;
            $this->fail('The policy themes facet should not be shown.');
        } catch (\Exception $e) {
            // Do nothing.
        }

        try {
            $this->advancedSearchFrontViewPage->settingsFilterFacet;
            $this->fail('The CIRRO settings facet should not be shown.');
        } catch (\Exception $e) {
            // Do nothing.
        }
    }
}
