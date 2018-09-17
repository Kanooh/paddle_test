<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Maps\NoSearchResultsMessageTest.
 */

namespace Kanooh\Paddle\App\Maps;

use Kanooh\Paddle\Core\Admin\Base\NoSearchResultsMessageTestBase;
use Kanooh\Paddle\Pages\Node\ViewPage\Maps\MapsViewPage;

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
        return $this->contentCreationService->createMapsPage();
    }

    /**
     * {@inheritdoc}
     */
    public function searchForSomethingThatReturnsNoResults($nid, $search_string)
    {
        $frontendViewPage = new MapsViewPage($this);
        $frontendViewPage->go($nid);
        $frontendViewPage->searchFormPane->form->keywords->fill($search_string);
        $frontendViewPage->searchFormPane->form->submit->click();
        $frontendViewPage->checkArrival();
    }
}
