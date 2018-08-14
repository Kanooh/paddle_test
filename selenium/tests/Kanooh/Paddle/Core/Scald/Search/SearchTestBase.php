<?php
/**
 * @file
 */

namespace Kanooh\Paddle\Core\Scald\Search;

use Kanooh\Paddle\Pages\Admin\ContentManager\AssetsPage\AssetsPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\Scald\Library;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\Paddle\Utilities\TaxonomyService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\Paddle\Pages\Element\Scald\LibraryItem;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

abstract class SearchTestBase extends WebDriverTestCase
{
    /**
     * Test data provider for alphanumeric data.
     *
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * @var AssetsPage
     */
    protected $assetsPage;

    /**
     * @var CleanUpService
     */
    protected $cleanUpService;

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
        // Create some instances to use later on.
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->assetCreationService = new AssetCreationService($this);
        $this->assetsPage = new AssetsPage($this);
        $this->cleanUpService = new CleanUpService($this);
        $this->taxonomyService = new TaxonomyService();
        $this->userSessionService = new UserSessionService($this);

        $drupal = new DrupalService();
        $drupal->bootstrap($this);

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Get properties one can search on with the full text search of the
     * Scald library.
     *
     * @return array
     *   A list of searchable properties.
     */
    final protected function assetSearchableProperties()
    {
        $atom_searchable_properties = array(
            'title',
            'alternative_text',
            'description',
        );

        return $atom_searchable_properties;
    }

    /**
     * Create image atoms filled with random data.
     *
     * @param int $amount
     *   How many image atoms to create.
     * @return array
     *   An array of data used to create the random atoms.
     */
    protected function createRandomImageAtoms($amount = 3)
    {
        $atoms = array();

        // Create 3 image atoms filled with random data.
        for ($i = 0; $i < $amount; $i++) {
            $atoms[$i] = $this->assetCreationService->createImage($this->getRandomImageAtomData($i));
        }

        return $atoms;
    }

    /**
     * Returns random metadata for image atoms.
     *
     * @param $prefix
     *   Prefix to ensure the data is unique.
     *
     * @return array
     *   List of metadata properties with random data, keyed by their field
     *   name.
     */
    public function getRandomImageAtomData($prefix)
    {
        $atom = array();

        $properties = array(
            'title',
            // This field is no longer used. But it might be reintroduced.
            // 'caption',
            'alternative_text',
            'description',
            'meta_data',
        );

        foreach ($properties as $property) {
            $atom[$property] = $prefix . $this->alphanumericTestDataProvider->getValidValue(8);
        }

        return $atom;
    }

    /**
     * Executes the necessary steps towards the Scald Library.
     */
    abstract protected function executeStepsTowardsLibrary();

    /**
     * Retrieve the library from the page.
     *
     * @return Library
     */
    abstract protected function getLibrary();

    /**
     * Basic test for the search on image atoms.
     *
     * @group scald
     */
    public function testSearchOnImageAtoms()
    {
        $atoms = $this->createRandomImageAtoms();
        $atom_searchable_properties = $this->assetSearchableProperties();

        $this->executeStepsTowardsLibrary();
        $library = $this->getLibrary();

        foreach ($atom_searchable_properties as $property) {
            foreach ($atoms as $atom) {
                $library->searchText->fill($atom[$property]);
                $library->searchButton->click();

                // Wait until the expected result set appears.
                $this->waitUntil(
                    function ($webdriver) use ($atom, $library) {
                        $items = $library->items;

                        /** @var LibraryItem $first_item */
                        $first_item = reset($items);

                        if (count($items) >= 1 && $first_item->atomId == $atom['id']) {
                            return true;
                        }
                    },
                    5000
                );
            }
        }
    }

    /**
     * Tests the taxonomy search on atoms.
     */
    public function testTaxonomySearchOnImageAtoms()
    {
        // Create four taxonomy terms for each type.
        // The tags are not really terms, just the name is needed.
        $terms = array();
        for ($i = 0; $i < 4; $i++) {
            $terms['tags'][] = $this->alphanumericTestDataProvider->getValidValue();
            $terms['general'][] = $this->taxonomyService->createTerm(
                TaxonomyService::GENERAL_TAGS_VOCABULARY_ID,
                $this->alphanumericTestDataProvider->getValidValue()
            );
        }

        // Manually create the fourth tag, as it won't be added directly to the
        // atoms.
        $this->taxonomyService->createTerm(
            taxonomyService::TAGS_VOCABULARY_ID,
            $terms['tags'][3]
        );

        // Create atoms to cover these cases:
        // - single term tagged atom;
        // - double term tagged atom;
        // - two terms tagged with the same term.
        $atoms = array();
        $atoms[] = $this->assetCreationService->createImage(array(
            'tags' => array($terms['tags'][0]),
            'general_terms' => array($terms['general'][0]),
        ));
        $atoms[] = $this->assetCreationService->createImage(array(
            'tags' => array($terms['tags'][1], $terms['tags'][2]),
            'general_terms' => array($terms['general'][1], $terms['general'][2]),
        ));
        $atoms[] = $this->assetCreationService->createImage(array(
            'tags' => array($terms['tags'][2]),
            'general_terms' => array($terms['general'][2]),
        ));

        // We need the general terms name, not ids, to do the searches.
        foreach (taxonomy_term_load_multiple($terms['general']) as $term) {
            $key = array_search($term->tid, $terms['general']);
            $terms['general'][$key] = $term->name;
        }

        // Go to the library.
        $this->executeStepsTowardsLibrary();

        // Test searches by tag and general term.
        foreach ($terms as $type => $data) {
            $field = ($type == 'tags') ? 'tagsAutocompleteField' : 'generalTermsAutocompleteField';

            // Clear the fields first, to avoid leftover filters.
            $library = $this->getLibrary();
            $library->tagsAutocompleteField->clear();
            $library->generalTermsAutocompleteField->clear();

            // If there is a failure in the next assertions, we won't be
            // able easily to find which term type failed. Prepare an
            // additional message to show to the developer.
            $assertion_message = sprintf('Failed asserting %s filter search results.', $type);

            // Search the first term.
            $this->setSearchTermAutocomplete($field, $data[0], true);
            // Get an updated library element.
            $library = $this->getLibrary();
            // Launch the search.
            $library->searchButton->click();
            $library->waitUntilReloaded();
            // We expect to find one result.
            $this->assertCount(1, $library->items, $assertion_message);
            $this->assertNotNull($library->getAtomById($atoms[0]), $assertion_message);

            // Search the second term.
            $this->setSearchTermAutocomplete($field, $data[1]);
            // Get an updated library element.
            $library = $this->getLibrary();
            // Launch the search.
            $library->searchButton->click();
            $library->waitUntilReloaded();
            // We expect to find one result.
            $this->assertCount(1, $library->items, $assertion_message);
            $this->assertNotNull($library->getAtomById($atoms[1]), $assertion_message);

            // Search the third term.
            $this->setSearchTermAutocomplete($field, $data[2]);
            // Get an updated library element.
            $library = $this->getLibrary();
            // Launch the search.
            $library->searchButton->click();
            $library->waitUntilReloaded();
            // We expect to find two results.
            $this->assertCount(2, $library->items, $assertion_message);
            $this->assertNotNull($library->getAtomById($atoms[1]), $assertion_message);
            $this->assertNotNull($library->getAtomById($atoms[2]), $assertion_message);

            // Search the fourth term.
            $this->setSearchTermAutocomplete($field, $data[3]);
            // Get an updated library element.
            $library = $this->getLibrary();
            // Launch the search.
            $library->searchButton->click();
            $library->waitUntilReloaded();
            // We expect to find no results.
            $this->assertCount(0, $library->items, $assertion_message);
        }

        // Search for both fields together.
        $this->setSearchTermAutocomplete('tagsAutocompleteField', $terms['tags'][1]);
        $this->setSearchTermAutocomplete('generalTermsAutocompleteField', $terms['general'][2]);

        // Get an updated library element.
        $library = $this->getLibrary();
        // This time, launch the search using the keyboard, to test
        // that the normal submit by enter works on the form.
        // We refocus the element because might have been lost due autocomplete.
        $this->getLibrary()->generalTermsAutocompleteField->getWebdriverElement()->click();
        $this->keys(Keys::ENTER);
        $library->waitUntilReloaded();

        // We expect to find one result.
        $this->assertCount(1, $library->items);
        $this->assertNotNull($library->getAtomById($atoms[1]));
    }

    /**
     * Tests that autosuggestions fields are using the correct vocabulary.
     *
     * This test might fail on waitUntilSuggestionCountEquals() with timeouts
     * if a regression is met.
     * This is because it waits for an exact element count to be available,
     * but in case of regression the element count will be different
     * (higher, probably).
     */
    public function testAutocompleteSuggestions()
    {
        // Generate a prefix that will be shared amongst taxonomy terms.
        $prefix = $this->alphanumericTestDataProvider->getValidValue(5);

        // Create one general term.
        $term_name = $prefix . $this->alphanumericTestDataProvider->getValidValue(7);
        $this->taxonomyService->createTerm(TaxonomyService::GENERAL_TAGS_VOCABULARY_ID, $term_name);

        // And one tag.
        $tag_name = $prefix . $this->alphanumericTestDataProvider->getValidValue(7);
        $this->taxonomyService->createTerm(taxonomyService::TAGS_VOCABULARY_ID, $tag_name);

        // Go to the library.
        $this->executeStepsTowardsLibrary();

        // Fill the tags field with the prefix and verify that is showing
        // only the tag term.
        $library = $this->getLibrary();
        $library->tagsAutocompleteField->fill($prefix);
        // Wait for autocomplete to kick in.
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilSuggestionCountEquals(1);
        // Verify that only the wanted suggestion is shown.
        $suggestions = $autocomplete->getSuggestions();
        $this->assertCount(1, $suggestions);
        $this->assertEquals($tag_name, reset($suggestions));

        // Now do the same with the general terms field.
        $library = $this->getLibrary();
        $library->generalTermsAutocompleteField->fill($prefix);
        // Wait for autocomplete to kick in.
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilSuggestionCountEquals(1);
        // Verify that only the wanted suggestion is shown.
        $suggestions = $autocomplete->getSuggestions();
        $this->assertCount(1, $suggestions);
        $this->assertEquals($term_name, reset($suggestions));
    }

    /**
     * Helper function to set an autocomplete field value.
     *
     * @param string $field
     *   The name of the field property.
     * @param string $value
     *   The valid term name to use as value.
     * @param bool $use_keyboard
     *   True to pick the suggestion using the keyboard. Default to false.
     */
    protected function setSearchTermAutocomplete($field, $value, $use_keyboard = false)
    {
        // Get an updated library element.
        $library = $this->getLibrary();

        // Fill the autocomplete field.
        $library->{$field}->fill($value);

        // Wait for autocomplete to kick in.
        $autocomplete = new AutoComplete($this);
        $autocomplete->waitUntilSuggestionCountEquals(1);
        $autocomplete->pickSuggestionByPosition(0, $use_keyboard);
        $autocomplete->waitUntilNoLongerDisplayed();
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Delete any assets created during the tests.
        AssetCreationService::cleanUp($this);
        // Clean taxonomy terms to avoid long list issues.
        $this->cleanUpService->deleteEntities('taxonomy_term');

        parent::tearDown();
    }
}
