<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Maps\Pane\TopSectionImagePresenceTest.
 */

namespace Kanooh\Paddle\App\Maps\Pane;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\Maps;
use Kanooh\Paddle\Core\Pane\Base\TopSectionImagePresenceTestBase;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\MapsPanelsContentType;

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

        $this->appService->enableApp(new Maps);
    }

    /**
     * {@inheritdoc}
     */
    public function createPaneWithTopImage($nid)
    {
        // Create an map to select in the pane.
        $maps_nid = $this->contentCreationService->createMapsPage();

        // Add an Maps pane to the test node.
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();
        $content_type = new MapsPanelsContentType($this);

        $webdriver = $this;
        $callable = new SerializableClosure(
            function () use ($webdriver, $content_type, $maps_nid) {
                $content_type->getForm()->autocompleteField->fill('node/' . $maps_nid);
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
