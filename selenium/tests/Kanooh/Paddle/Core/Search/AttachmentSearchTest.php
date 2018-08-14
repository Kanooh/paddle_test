<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Search\AttachmentSearchTest.
 */

namespace Kanooh\Paddle\Core\Search\Base;

use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Pages\SearchPage\PaddleSearchPage;

/**
 * Tests the search functionality.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class AttachmentSearchTest extends AttachmentSearchTestBase
{

    /**
     * @var FrontPage
     */
    protected $frontPage;

    /**
     * @var PaddleSearchPage
     */
    protected $searchPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        $this->frontPage = new FrontPage($this);
        $this->searchPage = new PaddleSearchPage($this);

        parent::setUpPage();
    }

    /**
     * {@inheritdoc}
     */
    public function searchFor($keyword)
    {
        $this->frontPage->go();
        $this->frontPage->searchBox->searchField->fill($keyword);
        $this->frontPage->searchBox->searchButton->click();
        $this->searchPage->checkArrival();
    }

    /**
     * {@inheritdoc}
     */
    public function getSearchResults()
    {
        return $this->searchPage->searchResults;
    }
}
