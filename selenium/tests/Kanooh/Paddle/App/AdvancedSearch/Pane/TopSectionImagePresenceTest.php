<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\AdvancedSearch\Pane\TopSectionImagePresenceTest.
 */

namespace Kanooh\Paddle\App\AdvancedSearch\Pane;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\AdvancedSearch;
use Kanooh\Paddle\Core\Pane\Base\TopSectionImagePresenceTestBase;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\AdvancedSearchPanelsContentType;

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

        $this->appService->enableApp(new AdvancedSearch);
    }

    /**
     * {@inheritdoc}
     */
    public function createPaneWithTopImage($nid)
    {
        // Create an advanced search page to select in the pane.
        $advanced_search_nid = $this->contentCreationService->createAdvancedSearchPage();

        // Add an Advanced search pane to the test node.
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();
        $content_type = new AdvancedSearchPanelsContentType($this);

        $webdriver = $this;
        $callable = new SerializableClosure(
            function () use ($webdriver, $content_type, $advanced_search_nid) {
                $content_type->getForm()->autocompleteField->fill('node/' . $advanced_search_nid);
                $autocomplete = new AutoComplete($webdriver);
                $autocomplete->pickSuggestionByPosition(0);
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
