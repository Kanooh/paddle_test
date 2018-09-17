<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Search\NoSearchResultsMessageTest.
 */

namespace Kanooh\Paddle\Core\Search;

use Kanooh\Paddle\Core\Admin\Base\NoSearchResultsMessageTestBase;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Pages\SearchPage\PaddleSearchPage;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class NoSearchResultsMessageTest extends NoSearchResultsMessageTestBase
{

    /**
     * {@inheritdoc}
     */
    public function setupNode()
    {
        // No specific page needed to test the Paddle core search.
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function searchForSomethingThatReturnsNoResults($nid, $search_string)
    {
        $frontPage = new FrontPage($this);
        $frontPage->go();
        $frontPage->searchBox->searchField->fill($search_string);
        $frontPage->searchBox->searchButton->click();
        $searchPage = new PaddleSearchPage($this);
        $searchPage->checkArrival();
    }
}
