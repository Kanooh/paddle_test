<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Search\SearchTest.
 */

namespace Kanooh\Paddle\Core\Search;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPage;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CustomContentPanelsContentType;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage as NodeEditPage;
use Kanooh\Paddle\Pages\SearchPage\PaddleSearchPage;
use Kanooh\Paddle\Traits\DataProvider\ThemeDataProviderTrait;
use Kanooh\Paddle\Utilities\TaxonomyService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalApi\DrupalSearchApiApi;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

/**
 * Tests the search functionality.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class SearchTest extends WebDriverTestCase
{

    /**
     * Use the theme data provider.
     */
    use ThemeDataProviderTrait;

    /**
     * @var ViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var DrupalSearchApiApi
     */
    protected $drupalSearchApiApi;

    /**
     * @var FrontPage
     */
    protected $frontPage;

    /**
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * @var NodeEditPage
     */
    protected $nodeEditPage;

    /**
     * @var array
     */
    protected $nodes = array();

    /**
     * @var PaddleSearchPage
     */
    protected $searchPage;

    /**
     * @var TaxonomyService
     */
    protected $taxonomyService;

    /**
     * @var ThemerOverviewPage
     */
    protected $themerOverviewPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        $this->administrativeNodeViewPage = new ViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->drupalSearchApiApi = new DrupalSearchApiApi($this);
        $this->frontPage = new FrontPage($this);
        $this->layoutPage = new LayoutPage($this);
        $this->nodeEditPage = new NodeEditPage($this);
        $this->searchPage = new PaddleSearchPage($this);
        $this->themerOverviewPage = new ThemerOverviewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->taxonomyService = new TaxonomyService();

        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Data provider for the type ahead test.
     */
    public function typeAheadWordDataProvider()
    {
        return array(
            array('Drupal', 'Lorem', 'Ipsum', 'Dolor', 'amet'),
            array('Serenity', 'Donec', 'Pretium', 'faucibus', 'tellus'),
        );
    }

    /**
     * Tests the type ahead functionality for the search field.
     *
     * @dataProvider typeAheadWordDataProvider
     *
     * @param string $title
     *   The title of the page.
     * @param string $body
     *   The text to use as body of the page.
     * @param string $teaser
     *   The text to use as summary of the page.
     * @param string $term
     *   The name to use to create a term in the general vocabulary.
     * @param string $tag
     *   The name to use to create a term in the tags vocabulary.
     *
     * @group search
     */
    public function testTypeAhead($title, $body, $teaser, $term, $tag)
    {
        // Create a general vocabulary term.
        $term_tid = $this->taxonomyService->createTerm(TaxonomyService::GENERAL_TAGS_VOCABULARY_ID, $term);

        // Create a node.
        $info = $this->createSearchableNode($title, $body, $teaser, $term_tid, $tag);

        // Mark the node for deletion at the end of the test.
        $this->nodes[] = $info['nid'];

        // Publish the node.
        $this->administrativeNodeViewPage->go($info['nid']);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Index the page and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Go to the search page
        $this->searchPage->go();

        // Assert that all the fields are used for the type ahead search.
        $this->assertTypeAheadSuggestion($title);
        $this->assertTypeAheadSuggestion($body);
        $this->assertTypeAheadSuggestion($teaser);
        $this->assertTypeAheadSuggestion($term);
        $this->assertTypeAheadSuggestion($tag);
    }

    /**
     * Tests that the search snippet matches our criteria.
     *
     * @group search
     */
    public function testSearchSnippets()
    {
        // Define a common word to share between all the nodes.
        $word = 'bird';

        // Keep an array of nodes to work with.
        $nodes = array();

        // Create a general tag with the word in it.
        $term_title = "$word " . $this->alphanumericTestDataProvider->getValidValue();
        $term_tid = $this->taxonomyService->createTerm(TaxonomyService::GENERAL_TAGS_VOCABULARY_ID, $term_title);

        // Create a node without body and the word in the title.
        $title = "$word a" . $this->alphanumericTestDataProvider->getValidValue();
        $info = $this->createSearchableNode($title);
        $nodes[] = $info;
        // Mark node for cleanup.
        $this->nodes[] = $info['nid'];

        // Create a node with body containing the word.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $body = $this->generateHTMLContent() . $word;
        $info = $this->createSearchableNode($title, $body);
        $nodes[] = $info;
        // Mark node for cleanup.
        $this->nodes[] = $info['nid'];

        // Create a node with a long body and no teaser.
        // The word can be found in the title.
        $title = "$word z" . $this->alphanumericTestDataProvider->getValidValue();
        $body = $this->generateHTMLContent();
        $info = $this->createSearchableNode($title, $body);
        $nodes[] = $info;
        // Mark node for cleanup.
        $this->nodes[] = $info['nid'];

        // Create a node with body and long teaser.
        // The word can be found in the general tag associated.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $body = $this->generateHTMLContent();
        // The teaser is a plain text field. Use an HTML content so we test
        // that our search snippet generation code strips the html.
        $teaser = $this->generateHTMLContent();
        $info = $this->createSearchableNode($title, $body, $teaser, $term_tid);
        $nodes[] = $info;
        // Mark node for cleanup.
        $this->nodes[] = $info['nid'];

        // Create a node with body and short teaser.
        // The word can be found in the tag associated.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $body = $this->generateHTMLContent();
        $teaser = implode(' ', $this->alphanumericTestDataProvider->getValidDataSet());
        $tag_title = "$word " . $this->alphanumericTestDataProvider->getValidValue();
        $info = $this->createSearchableNode($title, $body, $teaser, null, $tag_title);
        $nodes[] = $info;
        // Mark node for cleanup.
        $this->nodes[] = $info['nid'];

        // Publish all nodes.
        foreach ($nodes as $info) {
            $this->administrativeNodeViewPage->go($info['nid']);
            $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
            $this->administrativeNodeViewPage->checkArrival();
        }

        // Index all the nodes and commit the index itself.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Search for the common word.
        $this->frontPage->go();
        $this->frontPage->searchBox->searchField->fill($word);
        $this->frontPage->searchBox->searchButton->click();
        $this->searchPage->checkArrival();

        // Check that we have our 5 nodes.
        $results = $this->searchPage->searchResults->getResultsKeyedByTitle();
        $this->assertCount(5, $results);

        // The first node created has no body or teaser. The word is in the
        // title, so expect an empty snippet.
        /* @var \Kanooh\Paddle\Pages\SearchPage\SearchResult $current */
        $current = $results[$nodes[0]['title']];
        try {
            $current->snippet;
            $this->fail('Snippet found');
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // We cool, we cool. Snippet not found.
        }

        // The second node created has the word in the body. Expect a snippet
        // created by Solr/search_api module, that starts and ends with
        // triple dots, and not longer than 100 characters.
        // It should be around 60 plus the word, but it depends on the word
        // size.
        // @see search_api/includes/processor_highlight.inc
        $current = $results[$nodes[1]['title']];
        $snippet = $current->snippet;
        $this->assertRegExp("/^\\.\\.\\..+\\s{$word}\\s\\.\\.\\.$/", $snippet);
        $this->assertLessThanOrEqual(100, drupal_strlen($snippet));

        // The third node has the word in the title. Because of that,
        // the expected snippet will be generated automatically from the
        // body.
        $current = $results[$nodes[2]['title']];
        $snippet = $current->snippet;
        $expected_snippet = check_markup($nodes[2]['body'], 'full_html');
        $expected_snippet = text_summary(strip_tags(($expected_snippet)));
        // Remove new lines as they might interfere.
        $expected_snippet = preg_replace("/\n+/", ' ', $expected_snippet);
        $this->assertEquals($expected_snippet, $snippet);

        // The fourth node has the word in the general tags.
        // Expect a snippet created from his very long teaser.
        $current = $results[$nodes[3]['title']];
        $snippet = $current->snippet;
        $expected_snippet = check_markup($nodes[3]['teaser'], 'full_html');
        $expected_snippet = text_summary(strip_tags(($expected_snippet)));
        // Remove new lines as they might interfere.
        $expected_snippet = preg_replace("/\n+/", ' ', $expected_snippet);
        $this->assertEquals($expected_snippet, $snippet);

        // The fifth node has the word in the title.
        // Expect a snippet created using his short teaser.
        $current = $results[$nodes[4]['title']];
        $snippet = $current->snippet;
        $expected_snippet = check_markup($nodes[4]['teaser'], 'full_html');
        $expected_snippet = text_summary(strip_tags(($expected_snippet)));
        // Remove new lines as they might interfere.
        $expected_snippet = preg_replace("/\n+/", ' ', $expected_snippet);
        $this->assertEquals($expected_snippet, $snippet);
    }

    /**
     * Tests search in panes.
     *
     * @group search
     * @group panes
     */
    public function testSearchPane()
    {
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $nid = $this->contentCreationService->createBasicPage($title);
        $this->layoutPage->go($nid);

        $region = $this->layoutPage->display->getRandomRegion();

        $content_type = new CustomContentPanelsContentType($this);
        $word = $this->alphanumericTestDataProvider->getValidValue(16);
        $callable = new SerializableClosure(
            function () use ($content_type, $word) {
                $content_type->getForm()->body->setBodyText($word);
            }
        );

        $region->addPane($content_type, $callable);
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Index all the nodes and commit the index itself.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Search for the word.
        $this->frontPage->go();
        $this->frontPage->searchBox->searchField->fill($word);
        $this->frontPage->searchBox->searchButton->click();
        $this->searchPage->checkArrival();

        // Check that we have our node.
        $results = $this->searchPage->searchResults->getResults();
        $this->assertCount(1, $results);
        $this->assertTextPresent($title);
    }

    /**
     * Data provider for the minim should match test.
     *
     * The test data is composed by a value indicating the number of words
     * to search and another one indicating how many words should be present
     * for the search to start matching.
     * Example: 3 words to search, documents should be returned when at least
     * 2 words are matched.
     */
    public function minimumShouldMatchDataProvider()
    {
        return array(
            array(2, 2),
            array(3, 2),
            array(4, 3),
            array(5, 4),
            array(6, 4),
        );
    }

    /**
     * Tests the minimum 'should' match Solr parameter.
     *
     * @dataProvider minimumShouldMatchDataProvider
     *
     * @param int $word_search_count
     *   The number of words to search for.
     * @param int $minimum_words_match_count
     *   The expected minimum amount of words to find in the results.
     *
     * @group search
     */
    public function testMinimumShouldMatch($word_search_count, $minimum_words_match_count)
    {
        // Prepare a list of words to use.
        $words = array(
            'Kanooh',
            'CMS',
            'Drupal',
            'usability',
            'tested',
            'users',
        );

        // Create nodes with an incremental number of words in the title.
        $titles = array();
        for ($i = 1; $i < 7; $i++) {
            $title = implode(' ', array_slice($words, 0, $i));
            $info = $this->createSearchableNode($title);

            // Keep node title for later assertions.
            $titles[] = $title;

            // Mark the node for deletion.
            $this->nodes[] = $info['nid'];

            // Publish the node.
            $this->administrativeNodeViewPage->go($info['nid']);
            $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
            $this->administrativeNodeViewPage->checkArrival();
        }

        // Index the page and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Go to the search page
        $this->searchPage->go();

        // Search the terms of this test case.
        $search_terms = implode(' ', array_slice($words, 0, $word_search_count));
        $this->searchPage->form->keywords->fill($search_terms);
        $this->searchPage->form->submit->click();
        $this->searchPage->checkArrival();

        // Check that we have the expected nodes count.
        $results = $this->searchPage->searchResults->getResults();
        $this->assertCount(
            count($words) - $minimum_words_match_count + 1,
            $results
        );

        // The search results will contain first the exact match, then
        // the matches that have more words than the ones searched...
        $expected_titles = array_slice($titles, $word_search_count - 1);
        // ...followed by results with partial matching, starting with the
        // nodes with the most matches.
        $expected_titles = array_merge(
            $expected_titles,
            array_reverse(array_slice(
                $titles,
                $minimum_words_match_count - 1,
                $word_search_count - $minimum_words_match_count
            ))
        );

        // Fetch all the titles from the results. They will be ordered by
        // importance.
        $actual_titles = array();
        foreach ($results as $result) {
            $actual_titles[] = $result->title;
        }

        // Verify that the results are completely correct.
        $this->assertEquals($expected_titles, $actual_titles);
    }

    /**
     * Tests NGram filter applied when indexing content.
     *
     * @group search
     */
    public function testNGramFilter()
    {
        $compound = 'topsportrichting';
        $word = 'sport';

        // Create a node with a known title and the compound word as body.
        $info_compound = $this->createSearchableNode($this->alphanumericTestDataProvider->getValidValue(), $compound);

        // Create another node with the inner word only as body.
        $info_word = $this->createSearchableNode($this->alphanumericTestDataProvider->getValidValue(), $word);

        // Publish the pages.
        foreach (array($info_compound['nid'], $info_word['nid']) as $nid) {
            $this->administrativeNodeViewPage->go($nid);
            $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
            $this->administrativeNodeViewPage->checkArrival();
        }

        // Index the node and commit the index itself.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Search for the inner word.
        $this->frontPage->go();
        $this->frontPage->searchBox->searchField->fill($word);
        $this->frontPage->searchBox->searchButton->click();
        $this->searchPage->checkArrival();

        // Check that we have our nodes.
        $this->assertTextPresent($info_compound['title']);
        $this->assertTextPresent($info_word['title']);

        // Find which positions the compound and inner word nodes are shown.
        $results = $this->searchPage->searchResults->getResults();
        $word_position = -1;
        $compound_position = -2;
        foreach ($results as $key => $result) {
            $result_title = $result->title;
            if ($result_title == $info_compound['title']) {
                $compound_position = $key;
            } elseif ($result_title == $info_word['title']) {
                $word_position = $key;
            }
        }
        // Verify that the node that contains the inner word is listed before
        // the one that contains the compound word.
        $this->assertGreaterThan($word_position, $compound_position);

        // Search for the full compound word.
        $this->frontPage->go();
        $this->frontPage->searchBox->searchField->fill($compound);
        $this->frontPage->searchBox->searchButton->click();
        $this->searchPage->checkArrival();

        // Check that we have our node.
        $this->assertTextPresent($info_compound['title']);

        // Delete the nodes so further tests can work cleanly.
        $this->nodes = array(
            $info_compound['nid'],
            $info_word['nid'],
        );
    }

    /**
     * Tests the search functionality in the mobile viewport.
     *
     * @dataProvider themeDataProvider
     *
     * @param string $theme_name
     *   The name of the theme to test.
     * @param null|array $optional_modules
     *   An array of additional themes to enable. Defaults to none.
     *
     * @group search
     */
    public function testMobileSearch($theme_name, $optional_modules = null)
    {
        // Create a node.
        $title = $this->alphanumericTestDataProvider->getValidValue();
        $info = $this->createSearchableNode($title);

        // Mark the node for deletion at the end of the test.
        $this->nodes[] = $info['nid'];

        // Publish the node.
        $this->administrativeNodeViewPage->go($info['nid']);
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Index the page and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Switch to an user that can manage themes.
        $this->userSessionService->switchUser('SiteManager');

        // Enable modules if needed.
        if (!empty($optional_modules)) {
            module_enable($optional_modules);
            drupal_flush_all_caches();
        }

        // Preview the wanted theme.
        $this->themerOverviewPage->go();
        $this->themerOverviewPage->theme($theme_name)->preview->click();
        $this->frontPage->checkArrival();

        // Verify that the button to open the search is not displayed on
        // bigger viewports. Default is 1024.
        $this->assertFalse($this->frontPage->mobileSearchButton->displayed());
        // The search form elements should be there.
        $this->assertTrue($this->frontPage->searchForm->keywords->isDisplayed());
        $this->assertTrue($this->frontPage->searchForm->submit->displayed());

        // Resize to a mobile viewport size and verify that now the button is
        // shown.
        $this->resizeCurrentWindow(500);
        $testcase = $this;
        $callable = new SerializableClosure(
            function () use ($testcase) {
                if ($testcase->frontPage->mobileSearchButton->displayed()) {
                    return true;
                }
            }
        );
        $this->waitUntil($callable, $this->getTimeout());

        // The search form should not be shown yet.
        $this->frontPage->searchForm->waitUntilHidden();

        // Click the search toggle button.
        $this->frontPage->mobileSearchButton->click();
        // The search box should be there.
        $this->frontPage->searchForm->waitUntilVisible();

        // Click the search toggle button again.
        $this->frontPage->mobileSearchButton->click();
        // The search box should be hidden.
        $this->frontPage->searchForm->waitUntilHidden();

        // Click the search toggle button again so we can launch a search.
        $this->frontPage->mobileSearchButton->click();
        $this->frontPage->searchForm->waitUntilVisible();

        // Search for the created node.
        $this->frontPage->searchForm->keywords->fill($title);
        // The search button lies underneath an eventual suggestions
        // autocomplete showing up. Hit Escape to hide that.
        $this->keys(Keys::ESCAPE);
        // Safely launch the search now.
        $this->frontPage->searchForm->submit->click();
        $this->searchPage->checkArrival();

        // Verify that the search worked.
        $this->assertTextPresent($title);
    }

    /**
     * Generates html content for testing, with fixed tags and random text in it.
     *
     * @param int $paragraphs
     *   The number of random text paragraphs to add.
     * @param int $words_per_paragraph
     *   The number of words inside each paragraph.
     *
     * @return string
     *   The generated html content.
     */
    protected function generateHTMLContent($paragraphs = 4, $words_per_paragraph = 20)
    {
        // Append a fixed html at the beginning of the body.
        $body = '<h2>Lorem ipsum dolor.</h2>';
        $body .= '<ul><li>Lorem ipsum.</li><li>Ea, magni.</li><li>Facilis, nesciunt!</li></ul>';

        for ($i = 0; $i < $paragraphs; $i++) {
            $body .= '<p>';
            $body .= implode(' ', $this->alphanumericTestDataProvider->getValidDataSet($words_per_paragraph));
            $body .= '</p>';
        }

        return $body;
    }

    /**
     * Create a node with the provided values.
     *
     * @param string $title
     *   The string to use as page title.
     * @param null|string $body
     *   The string to use as page body. Null for an empty body.
     * @param null|string $teaser
     *   The string to use as page teaser. Null for an empty teaser.
     * @param null|int $term_tid
     *   The general tags term id to use with the page. Null for no tags.
     * @param null|int $tag_title
     *   The tags term title to tag the page with. Null for no tagging.
     *
     * @return array
     *   An array of node information.
     */
    protected function createSearchableNode($title, $body = null, $teaser = null, $term_tid = null, $tag_title = null)
    {
        // Create the basic page.
        $nid = $this->contentCreationService->createBasicPage($title);

        // Prepare the info to be returned.
        $info = array(
            'nid' => $nid,
            'title' => $title,
        );

        // Get the function arguments.
        $args = func_get_args();
        // Remove the title from the list.
        array_shift($args);
        // Remove null arguments.
        $args = array_filter($args);

        // If no other arguments are present, return the current info.
        if (!count($args)) {
            return $info;
        }

        // Go to the edit page.
        $this->nodeEditPage->go($nid);

        // Handle the body value.
        if ($body) {
            $this->nodeEditPage->body->waitUntilReady();
            $this->nodeEditPage->body->buttonSource->click();
            $this->nodeEditPage->body->setBodyText($body);
            $info['body'] = $body;
        }

        // Handle the teaser value.
        if ($teaser) {
            $this->nodeEditPage->teaserToggleLink->click();
            $this->nodeEditPage->waitUntilTeaserIsDisplayed();
            $this->nodeEditPage->teaser->fill($teaser);
            $info['teaser'] = $teaser;
        }

        // Handle the general term.
        if ($term_tid) {
            $this->nodeEditPage
                ->generalVocabularyTermReferenceTree
                ->getTermById($term_tid)
                ->select();
        }

        if ($tag_title) {
            $this->nodeEditPage->tags->value($tag_title);
            $this->nodeEditPage->tagsAddButton->click();
            $this->nodeEditPage->waitUntilTagIsDisplayed(ucfirst($tag_title));
        }

        // Save the node.
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        return $info;
    }

    /**
     * Asserts that a certain word is returned as type ahead suggestion.
     *
     * @param string $word
     *   The string to search for.
     */
    protected function assertTypeAheadSuggestion($word)
    {
        $word_part = substr($word, 0, 3);
        $this->searchPage->form->keywords->fill($word_part);

        // Get the suggestions and verify it shows the full word.
        $autocomplete = new AutoComplete($this);
        $suggestions = $autocomplete->getSuggestions();
        $suggestions = array_map('strtolower', $suggestions);

        $this->assertContains(strtolower($word), $suggestions);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Delete all nodes created by this test.
        if (!empty($this->nodes)) {
            node_delete_multiple($this->nodes);
        }

        parent::tearDown();
    }
}
