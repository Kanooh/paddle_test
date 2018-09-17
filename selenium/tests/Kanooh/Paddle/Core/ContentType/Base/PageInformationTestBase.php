<?php

/**
 * @file
 * Contains \Kanooh\Paddle\PageInformationTestBase.
 */

namespace Kanooh\Paddle\Core\ContentType\Base;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Admin\Structure\TaxonomyManager\OverviewPage\OverviewPage as TaxonomyOverviewPage;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

abstract class PageInformationTestBase extends WebDriverTestCase
{
    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * The node edit page.
     *
     * @var EditPage
     */
    protected $editPage;

    /**
     * The random data generation class.
     *
     * @var Random
     */
    protected $random;

    /**
     * The taxonomy overview page.
     *
     * @var TaxonomyOverviewPage
     */
    protected $taxonomyOverview;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * The 'Add' page of the Paddle Content Manager module.
     *
     * @var AddPage
     */
    protected $addContentPage;

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

        $this->addContentPage = new AddPage($this);
        $this->editPage = new EditPage($this);
        $this->taxonomyOverview = new TaxonomyOverviewPage($this);
        $this->random = new Random();
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Tests page information.
     *
     * @todo Split this test into several smaller tests.
     *
     * @group contentType
     * @group editing
     * @group pageInformationTestBase
     */
    public function testPageInformation()
    {
        // We need at least 2 taxonomy terms in the general vocabulary to test
        // the term reference tree later on the edit page.
        $this->taxonomyOverview->go(TaxonomyOverviewPage::GENERAL_TAGS_VOCABULARY_ID);

        $first_term = array(
            'name' => trim($this->random->name(8)),
            'description' => $this->random->string(16),
        );
        $first_tid = $this->taxonomyOverview->createTerm($first_term);

        $second_term = array(
            'name' => trim($this->random->name(8)),
            'description' => $this->random->string(16),
            'parent' => $first_tid,
        );
        $second_tid = $this->taxonomyOverview->createTerm($second_term, array($first_tid));

        // Create a test node and go to its edit page.
        $nid = $this->setupNode();
        $this->editPage->go($nid);

        $this->assertNotNull($this->editPage->seoTitleField);
        $this->assertNotNull($this->editPage->seoDescriptionField);
        $this->assertNotNull($this->editPage->navigationContainer);
        $this->assertNotNull($this->editPage->showBreadcrumbCheckbox);
        $this->assertNotNull($this->editPage->showNextLevelCheckbox);

        // Make sure the general vocabulary term reference tree is minimized. Do
        // this by looping over all terms in the root of the tree and making
        // sure their child terms are invisible.
        $terms = $this->editPage->generalVocabularyTermReferenceTree->getChildTerms();

        /** @var \Kanooh\Paddle\Pages\Element\TermReferenceTree\TermReferenceTreeElement $term */
        foreach ($terms as $term) {
            $visible = $term->hasVisibleChildren();
            $this->assertFalse($visible);
        }

        $this->editPage->checkPath();

        // Generate a random path alias.
        $alias = $this->random->name(16);
        // Ensure the randomly generated alias does not have a trailing slash.
        $alias = rtrim($alias, '/');

        // Try to add a trailing slash to the url alias, and make sure we get a validation error.
        $this->editPage->pathAuto->click();
        $this->editPage->pathAlias->value($alias . '/');
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->assertTrue($this->isTextPresent('URL alias should not have a trailing "/" character.'));

        // Now set a valid url alias, and make sure that it works.
        $this->editPage->pathAlias->clear();
        $this->editPage->pathAlias->value($alias);
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->assertFalse($this->isTextPresent('Page not found'));

        $view_page = new ViewPage($this);
        $view_page->checkArrival();

        $view_page->contextualToolbar->buttonPageProperties->click();
        $this->editPage->checkArrival();

        $this->assertNotNull($this->editPage->revisionHistory);
        $this->editPage->contextualToolbar->buttonSave->click();
    }

    /**
     * Tests the generated URL alias
     *
     * @group contentType
     * @group editing
     * @group pageInformationTestBase
     */
    public function testUrlAlias()
    {
        // Create 2 test nodes with title all the words that were stripped out
        // by pathauto. We need to create 2 nodes because there is a limit on
        // the number of characters in a path.
        $title_1 = 'a an as at before but by for from is in into like of off';
        $this->checkAlias($title_1);
        $title_2 = 'on onto per since than the this that to up via with';
        $this->checkAlias($title_2);
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
     * Checks if the path alias contains a given string.
     *
     * @param string $string
     *   The string to search for in the path alias.
     */
    private function checkAlias($string)
    {
        // Create a node.
        $nid = $this->setupNode($string);

        // Go to the node edit page.
        $this->editPage->go($nid);

        // Get the value for the path alias and check if it contains the words
        // set in the node title.
        $path_alias = $this->editPage->pathAlias->value();
        $string_alias = str_replace(' ', '-', $string);
        $this->assertTrue(strpos($path_alias, $string_alias) !== false);
        $this->editPage->contextualToolbar->buttonBack->click();
        $view_page = new ViewPage($this);
        $view_page->checkArrival();
    }
}
