<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Product\TopSectionImagePresenceTest.
 */

namespace Kanooh\Paddle\App\Poll;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\Poll;
use Kanooh\Paddle\Core\Pane\Base\TopSectionImagePresenceTestBase;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\PollPanelsContentType;

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

        $this->appService->enableApp(new Poll);
    }

    /**
     * {@inheritdoc}
     */
    public function createPaneWithTopImage($nid)
    {
        // Create an organizational unit to select in the pane.
        $poll_nid = $this->contentCreationService->createPollPage();

        // Add a Product pane to the test node.
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();
        $content_type = new PollPanelsContentType($this);

        $webdriver = $this;
        $callable = new SerializableClosure(
            function () use ($webdriver, $content_type, $poll_nid) {
                $content_type->getForm()->autocompleteField->fill('node/' . $poll_nid);
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
