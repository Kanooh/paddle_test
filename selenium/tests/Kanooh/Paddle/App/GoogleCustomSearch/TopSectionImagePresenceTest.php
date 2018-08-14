<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\GoogleCustomSearch\TopSectionImagePresenceTest.
 */

namespace Kanooh\Paddle\App\GoogleCustomSearch;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\GoogleCustomSearch;
use Kanooh\Paddle\Core\Pane\Base\TopSectionImagePresenceTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\GoogleCustomSearchPanelsContentType;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class TopSectionImagePresenceTest extends TopSectionImagePresenceTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new GoogleCustomSearch);
    }

    /**
     * {@inheritdoc}
     */
    public function createPaneWithTopImage($nid)
    {
        // Add a Google Custom Search pane to the test node.
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();
        $content_type = new GoogleCustomSearchPanelsContentType($this);

        $callable = new SerializableClosure(
            function () use ($content_type) {
            }
        );
        $pane = $region->addPane($content_type, $callable);

        // Edit it to add top image to it.
        $this->addTopImageToPane($pane, $content_type);

        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();
        return $pane->getUuid();
    }
}
