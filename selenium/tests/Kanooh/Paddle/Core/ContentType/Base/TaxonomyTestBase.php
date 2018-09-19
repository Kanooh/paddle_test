<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\ContentType\Base\TaxonomyTestBase.
 */

namespace Kanooh\Paddle\Core\ContentType\Base;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Admin\Structure\TaxonomyManager\OverviewPage\OverviewPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPageRandomFiller;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage as NodeEditPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for the taxonomy tests.
 */
abstract class TaxonomyTestBase extends WebDriverTestCase
{

    /**
     * The 'Add' page of the Paddle Content Manager module.
     *
     * @var AddPage
     */
    protected $addContentPage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The node edit page.
     *
     * @var NodeEditPage
     */
    protected $nodeEditPage;

    /**
     * The Taxonomy overview page.
     *
     * @var OverviewPage
     */
    protected $taxonomyOverviewPage;

    /**
     * The administrative node view of a landing page.
     *
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * The random data generation class.
     *
     * @var Random
     */
    protected $random;

    /**
     * Creates a node of the content type that is being tested.
     *
     * @param string $title
     *   Optional title for the node. If omitted a random title will be used.
     *
     * @return int
     *   The node ID of the node that was created.
     */
    abstract public function setupNode($title = null);

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Instantiate some objects for later use.
        $this->addContentPage = new AddPage($this);
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->nodeEditPage = new NodeEditPage($this);
        $this->random = new Random();
        $this->taxonomyOverviewPage = new OverviewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Go to the login page and log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        $this->contentCreationService->cleanUp($this);
        parent::tearDown();
    }

    /**
     * Tests the setting of terms of both taxonomy vocabularies.
     *
     * @group editing
     * @group taxonomy
     */
    public function testTaxonomyVocabularies()
    {
        // We need at least 2 taxonomy terms in the general vocabulary to test
        // the term reference tree later on the edit page.
        $this->taxonomyOverviewPage->go(OverviewPage::GENERAL_TAGS_VOCABULARY_ID);

        $first_term = array(
            'name' => trim($this->random->name(8)),
            'description' => $this->random->string(16),
        );
        $first_tid = $this->taxonomyOverviewPage->createTerm($first_term);

        $second_term = array(
            'name' => trim($this->random->name(8)),
            'description' => $this->random->string(16),
            'parent' => $first_tid,
        );
        $second_tid = $this->taxonomyOverviewPage->createTerm($second_term, array($first_tid));

        // Create a new node, with some terms set.
        $nid = $this->setupNode();
        $this->nodeEditPage->go($nid);

        // Make sure the taxonomy fieldset is expanded by default.
        $taxonomy_fieldset = $this->nodeEditPage->taxonomyContainer;
        $classes = explode(' ', $taxonomy_fieldset->attribute('class'));
        $this->assertFalse(in_array('folded', $classes));

        // Verify that only the first level is shown initially.
        $this->assertTrue($this->nodeEditPage->generalVocabularyTermReferenceTree->checkTermVisibile($first_tid));
        $this->assertFalse($this->nodeEditPage->generalVocabularyTermReferenceTree->checkTermVisibile($second_tid));

        // Expand all terms in the tree. Select a parent term, and make sure
        // the child term gets selected as well. (And vice versa.)
        $this->nodeEditPage->generalVocabularyTermReferenceTree->expandAllTerms();
        $parent = $this->nodeEditPage->generalVocabularyTermReferenceTree->getTermById($first_tid);
        $child = $this->nodeEditPage->generalVocabularyTermReferenceTree->getTermById($second_tid);

        // Checking the parent should also check the child.
        $parent->select();
        $this->assertTrue($child->selected());

        // Unchecking the parent should also uncheck the child.
        $parent->deselect();
        $this->assertFalse($child->selected());

        // Checking the child should also check the parent.
        $child->select();
        $this->assertTrue($parent->selected());

        // Unchecking the child should NOT uncheck the parent.
        $child->deselect();
        $this->assertTrue($parent->selected());

        $filler = new EditPageRandomFiller($this->nodeEditPage);
        $filler->setTargetFields($filler::TAGS_FIELD | $filler::GENERAL_TAGS_FIELD);
        $filler->randomize()->fill();

        // Override the random general tags. We only want tags from the first
        // level, as having tags on other levels will also select the parent
        // tags in the tree causing unwanted terms to be selected.
        $filler->generalTags = array();
        $tree = $this->nodeEditPage->generalVocabularyTermReferenceTree;
        $terms = $tree->getChildTerms();
        foreach ($terms as $term) {
            $filler->generalTags[] = $term->getTid();
        }

        $filler->fill();

        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        $this->administrativeNodeViewPage->contextualToolbar->buttonPageProperties->click();
        // Verify that only the first level is shown initially.
        $this->assertTrue($this->nodeEditPage->generalVocabularyTermReferenceTree->checkTermVisibile($first_tid));
        $this->assertFalse($this->nodeEditPage->generalVocabularyTermReferenceTree->checkTermVisibile($second_tid));
        $this->nodeEditPage->checkArrival();

        // Check that the tags were correctly saved.
        foreach ($filler->tags as $tag) {
            $this->nodeEditPage->waitUntilTagIsDisplayed($tag);
        }

        // Check that the general tags were correctly saved. Only check the
        // first level terms, because if there are terms on deeper levels those
        // get selected as well when their parent term is selected, which causes
        // unexpected term ids to be selected.
        $terms = $tree->getChildTerms();
        foreach ($terms as $term) {
            $expected = in_array($term->getTid(), $filler->generalTags);
            $actual = $term->selected();
            $this->assertEquals($expected, $actual);
        }

        // Go back to the administrative node view to prevent subsequent tests
        // from being confronted with an alert box.
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
    }
}
