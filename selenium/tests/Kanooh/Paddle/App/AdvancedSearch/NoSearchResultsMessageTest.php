<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\AdvancedSearch\NoSearchResultsMessageTest.
 */

namespace Kanooh\Paddle\App\AdvancedSearch;

use Kanooh\Paddle\Core\Admin\Base\NoSearchResultsMessageTestBase;
use Kanooh\Paddle\Pages\Node\ViewPage\AdvancedSearch\AdvancedSearchViewPage;

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
        return $this->contentCreationService->createAdvancedSearchPage();
    }

    /**
     * {@inheritdoc}
     */
    public function searchForSomethingThatReturnsNoResults($nid, $search_string)
    {
        $frontendViewPage = new AdvancedSearchViewPage($this);
        $frontendViewPage->go($nid);
        $frontendViewPage->searchFormPane->form->keywords->fill($search_string);
        $frontendViewPage->searchFormPane->form->submit->click();
        $frontendViewPage->checkArrival();
    }
}
