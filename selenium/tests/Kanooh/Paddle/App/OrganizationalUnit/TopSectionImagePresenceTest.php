<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\OrganizationalUnit\TopSectionImagePresenceTest.
 */

namespace Kanooh\Paddle\App\OrganizationalUnit;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\OrganizationalUnit;
use Kanooh\Paddle\Core\Pane\Base\TopSectionImagePresenceTestBase;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\OrganizationalUnitPanelsContentType;

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

        $this->appService->enableApp(new OrganizationalUnit);
    }

    /**
     * {@inheritdoc}
     */
    public function createPaneWithTopImage($nid)
    {
        // Create an organizational unit to select in the pane.
        $organizational_unit_nid = $this->contentCreationService->createOrganizationalUnit();

        // Add a Organizational Unit pane to the test node.
        $this->layoutPage->go($nid);
        $region = $this->layoutPage->display->getRandomRegion();
        $content_type = new OrganizationalUnitPanelsContentType($this);

        $webdriver = $this;
        $callable = new SerializableClosure(
            function () use ($webdriver, $content_type, $organizational_unit_nid) {
                $content_type->getForm()->organizationalUnitAutocompleteField->fill('node/' . $organizational_unit_nid);
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
