<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\AdvancedSearch\AttachmentSearchTest.
 */

namespace Kanooh\Paddle\App\AdvancedSearch;

use Kanooh\Paddle\Core\Search\Base\AttachmentSearchTestBase;
use Kanooh\Paddle\Pages\Node\ViewPage\AdvancedSearch\AdvancedSearchViewPage;

/**
 * Tests the search functionality.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class AttachmentSearchTest extends AttachmentSearchTestBase
{

    /**
     * @var AdvancedSearchViewPage
     */
    protected $advancedSearchPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        $this->advancedSearchPage = new AdvancedSearchViewPage($this);

        parent::setUpPage();
    }

    /**
     * {@inheritdoc}
     */
    public function searchFor($keyword)
    {
        $advanced_search_page_nid = $this->contentCreationService->createAdvancedSearchPage();
        $this->advancedSearchPage->go($advanced_search_page_nid);
        $this->advancedSearchPage->searchFormPane->form->keywords->fill($keyword);
        $this->advancedSearchPage->searchFormPane->form->submit->click();
        $this->advancedSearchPage->checkArrival();
    }

    /**
     * {@inheritdoc}
     */
    public function getSearchResults()
    {
        return $this->advancedSearchPage->searchResultsPane->results;
    }
}
