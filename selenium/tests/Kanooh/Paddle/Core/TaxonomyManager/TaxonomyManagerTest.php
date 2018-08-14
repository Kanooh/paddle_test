<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\TaxonomyManager\TaxonomyManagerTest.
 */

namespace Kanooh\Paddle\Core\TaxonomyManager;

use Kanooh\Paddle\Pages\Admin\Structure\TaxonomyManager\OverviewPage\OverviewPage;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\Paddle\Utilities\TaxonomyService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Test the UI for the Big Vocabularies functionality.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class TaxonomyManagerTest extends WebDriverTestCase
{
    /**
     * The alphanumeric test data provider.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * The Drupal utility service.
     *
     * @var DrupalService
     */
    protected $drupalService;

    /**
     * The Taxonomy overview page.
     *
     * @var OverviewPage
     */
    protected $taxonomyOverviewPage;

    /**
     * Taxonomy service.
     *
     * @var TaxonomyService
     */
    protected $taxonomyService;

    /**
     * The taxonomy terms created by this test.
     *
     * @var array
     */
    protected $taxonomyTerms = array();

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
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->drupalService = new DrupalService();
        $this->taxonomyOverviewPage = new OverviewPage($this);
        $this->taxonomyService = new TaxonomyService($this);
        $this->userSessionService = new UserSessionService($this);

        $this->drupalService->bootstrap($this);

        // Go to the login page and log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Delete all the taxonomy terms created by this test.
        if (!empty($this->taxonomyTerms)) {
            foreach ($this->taxonomyTerms as $key => $structure) {
                // Deleting parents will delete children in a non-multi parent
                // environment.
                taxonomy_term_delete($structure['#tid']);
            }
        }

        // Log out
        $this->userSessionService = new UserSessionService($this);
        $this->userSessionService->logout();

        parent::tearDown();
    }

    /**
     * Test the big vocabulary functionality on the taxonomy overview page.
     *
     * @todo Test the drag & drop functionality.
     *
     * @group taxonomy
     */
    public function testBigVocabulariesFunctionality()
    {
        $this->taxonomyOverviewPage->go(array(2));

        // Get the number of terms (level 1) we have when we start.
        $starting_terms = $this->taxonomyOverviewPage->vocabularyTable->getNumberOfRows();
        // Remove the empty row from the calculations.
        if ($this->isTextPresent('No terms available')) {
            $starting_terms--;
        }

        // Create a big hierarchy of terms.
        $this->taxonomyTerms = $this->taxonomyService->createHierarchicalStructure(2, 4, 3);

        // Reload the page.
        $this->taxonomyOverviewPage->go(array(2));

        // Check the number of terms visible. Only the level 1 items should be
        // visible.
        $expected_terms = 3 + $starting_terms;
        $this->assertEquals($expected_terms, $this->taxonomyOverviewPage->vocabularyTable->getNumberOfRows());

        // Clean up. Deleting the level 1 elements deletes everything below.
        foreach ($this->taxonomyTerms as $value) {
            taxonomy_term_delete($value['#tid']);
        }

        // Reload the page.
        $this->taxonomyOverviewPage->go(array(2));

        // We should have none of the terms we created.
        $current_number_terms = $this->taxonomyOverviewPage->vocabularyTable->getNumberOfRows();
        if ($this->isTextPresent('No terms available')) {
            $current_number_terms--;
        }

        $this->assertEquals($starting_terms, $current_number_terms);
    }
}
