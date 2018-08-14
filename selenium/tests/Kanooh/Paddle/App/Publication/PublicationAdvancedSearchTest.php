<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Publication\PublicationAdvancedSearchTest.
 */

namespace Kanooh\Paddle\App\Publication;

use Kanooh\Paddle\Apps\Publication;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Element\Publication\AuthorsTableRow;
use Kanooh\Paddle\Pages\Node\EditPage\AdvancedSearch\AdvancedSearchPage;
use Kanooh\Paddle\Pages\Node\EditPage\Publication\PublicationEditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\AdvancedSearch\AdvancedSearchViewPage;
use Kanooh\Paddle\Pages\Node\ViewPage\Publication\PublicationViewPage;
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
class PublicationAdvancedSearchTest extends WebDriverTestCase
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
     * @var PublicationEditPage
     */
    protected $publicationEditPage;

    /**
     * @var PublicationViewPage
     */
    protected $publicationFrontPage;

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
        $this->publicationEditPage = new PublicationEditPage($this);
        $this->publicationFrontPage = new PublicationViewPage($this);
        $this->taxonomyService = new TaxonomyService();

        // Go to the login page and log in as Chief Editor.
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new Publication);
    }

    /**
     * Tests the extra fields on the advanced search page.
     *
     * @group facets
     */
    public function testAdvancedSearchExtraFields()
    {
        // Create a publication.
        $pub_title = $this->alphanumericTestDataProvider->getValidValue();
        $pub_nid = $this->contentCreationService->createPublicationPage($pub_title);

        // Create an advanced search page.
        $nid = $this->contentCreationService->createAdvancedSearchPage();
        variable_set('publications_advanced_search_page', $nid);

        $author = $this->alphanumericTestDataProvider->getValidValue();
        $year = $this->alphanumericTestDataProvider->generateRandomInteger(1, 9999);
        $keyword = $this->alphanumericTestDataProvider->getValidValue();
        $authors_voc = taxonomy_vocabulary_machine_name_load('paddle_authors');
        $pub_year_voc = taxonomy_vocabulary_machine_name_load('paddle_publication_year');
        $keyword_voc = taxonomy_vocabulary_machine_name_load('paddle_keywords');
        $author_tid = $this->taxonomyService->createTerm($authors_voc->vid, $author);
        $year_tid = $this->taxonomyService->createTerm($pub_year_voc->vid, $year);
        $keyword_tid = $this->taxonomyService->createTerm($keyword_voc->vid, $keyword);

        // Go to the publication edit page and add an author and publication
        // year.
        $this->publicationEditPage->go($pub_nid);

        $rows = $this->publicationEditPage->publicationEditForm->authorsTable->rows;
        /** @var AuthorsTableRow $row */
        $row = reset($rows);
        $row->name->fill($author);

        $this->publicationEditPage->publicationEditForm->publicationYear->fill($year);

        $this->publicationEditPage->publicationEditForm->keywords->fill($keyword);
        $this->publicationEditPage->publicationEditForm->keywordsAddButton->click();
        $this->publicationEditPage->contextualToolbar->buttonSave->click();

        // Publish the publication.
        $this->administrativeNodeViewPage->checkArrival();
        $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Reindex the node index.
        // Index all the nodes and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Go to the advanced search edit page and check the new fields.
        $this->advancedSearchEditPage->go($nid);
        $this->assertFalse($this->advancedSearchEditPage->advancedSearchForm->filterAuthorsCheckbox->isChecked());
        $this->assertFalse($this->advancedSearchEditPage->advancedSearchForm->filterPublicationYearCheckbox->isChecked());
        $this->advancedSearchEditPage->advancedSearchForm->filterPublicationYearCheckbox->check();
        $this->advancedSearchEditPage->advancedSearchForm->filterAuthorsCheckbox->check();
        $this->advancedSearchEditPage->advancedSearchForm->filterKeywordsCheckbox->check();

        $this->advancedSearchEditPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Go to the front end of the advanced search page and verify 2 new
        // panes with the correct content are shown.
        $this->advancedSearchFrontViewPage->go($nid);
        $this->assertNotEmpty($this->advancedSearchFrontViewPage->publicationYearFilterFacet->getInactiveLinkByValue($year_tid));
        $this->assertNotEmpty($this->advancedSearchFrontViewPage->authorsFilterFacet->getInactiveLinkByValue($author_tid));
        $this->assertNotEmpty($this->advancedSearchFrontViewPage->keywordsFilterFacet->getInactiveLinkByValue($keyword_tid));

        // Go to the frontend page of the publication and verify that the facets are
        // shown
        $this->publicationFrontPage->go($pub_nid);
        $xpath = '//div[contains(@class, "pane-related-info")]//a[contains(@href, "f%5B0%5D=pas_publication_authors%3A' . $author_tid . '")]';
        $element = $this->byXPath($xpath);
        $element->click();
        $this->advancedSearchFrontViewPage->checkArrival();
        $this->assertNotEmpty($this->advancedSearchFrontViewPage->publicationYearFilterFacet->getInactiveLinkByValue($year_tid));
        $this->assertNotEmpty($this->advancedSearchFrontViewPage->authorsFilterFacet->getActiveLinkByValue($author_tid));
        $this->assertNotEmpty($this->advancedSearchFrontViewPage->keywordsFilterFacet->getInactiveLinkByValue($keyword_tid));

        // Now click on the keyword instead of on the author to reach the front-end page.
        $this->publicationFrontPage->go($pub_nid);
        $xpath = '//div[contains(@class, "pane-related-info")]//a[contains(@href, "f%5B0%5D=pas_publication_keywords%3A' . $keyword_tid . '")]';
        $element = $this->byXPath($xpath);
        $element->click();
        $this->advancedSearchFrontViewPage->checkArrival();
        $this->assertNotEmpty($this->advancedSearchFrontViewPage->publicationYearFilterFacet->getInactiveLinkByValue($year_tid));
        $this->assertNotEmpty($this->advancedSearchFrontViewPage->authorsFilterFacet->getInactiveLinkByValue($author_tid));
        $this->assertNotEmpty($this->advancedSearchFrontViewPage->keywordsFilterFacet->getActiveLinkByValue($keyword_tid));

        // Uncheck the checkboxes and verify that the panes are gone.
        $this->advancedSearchEditPage->go($nid);
        $this->assertTrue($this->advancedSearchEditPage->advancedSearchForm->filterAuthorsCheckbox->isChecked());
        $this->assertTrue($this->advancedSearchEditPage->advancedSearchForm->filterPublicationYearCheckbox->isChecked());
        $this->assertTrue($this->advancedSearchEditPage->advancedSearchForm->filterKeywordsCheckbox->isChecked());
        $this->advancedSearchEditPage->advancedSearchForm->filterPublicationYearCheckbox->uncheck();
        $this->advancedSearchEditPage->advancedSearchForm->filterAuthorsCheckbox->uncheck();
        $this->advancedSearchEditPage->advancedSearchForm->filterKeywordsCheckbox->uncheck();

        $this->advancedSearchEditPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        $this->advancedSearchFrontViewPage->go($nid);

        try {
            $this->advancedSearchFrontViewPage->publicationYearFilterFacet;
            $this->fail('The publication year facet should not be shown.');
        } catch (\Exception $e) {
            // Do nothing.
        }

        try {
            $this->advancedSearchFrontViewPage->authorsFilterFacet;
            $this->fail('The authors facet should not be shown.');
        } catch (\Exception $e) {
            // Do nothing.
        }

        try {
            $this->advancedSearchFrontViewPage->keywordsFilterFacet;
            $this->fail('The keywords facet should not be shown.');
        } catch (\Exception $e) {
            // Do nothing.
        }
    }
}
