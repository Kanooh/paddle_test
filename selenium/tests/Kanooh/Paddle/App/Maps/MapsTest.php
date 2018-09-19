<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Maps\Maps.
 */

namespace Kanooh\Paddle\App\Maps;

use Kanooh\Paddle\Pages\SearchPage\SearchResult;

/**
 * Performs tests on the Maps Paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class MapsTest extends MapsTestBase
{

    /**
     * Tests the default layout set when creating a new page.
     */
    public function testDefaultLayout()
    {
        // Create an advanced search page.
        $this->contentCreationService->createMapsPageViaUI();
        $this->adminNodeViewPage->checkClassPresent('paddle-layout-paddle_2_col_3_9');
    }

    /**
     * Test if the "Change layout" functionality works properly.
     *
     * The change is applied and it is visible immediately.
     *
     * @group panes
     */
    public function testChangeLayout()
    {
        // Create an advanced search page and go to the page layout.
        $nid = $this->contentCreationService->createMapsPage();
        $this->layoutPage->go($nid);

        // Get the current layout and supported layouts.
        $curr_layout = $this->layoutPage->display->getCurrentLayoutId();
        $allowed_layouts = $this->layoutPage->display->getSupportedLayouts();

        // Unset the current layout.
        unset($allowed_layouts[$curr_layout]);
        $random_layout = array_rand($allowed_layouts);

        // Change the layout.
        $this->layoutPage->changeLayout($random_layout);

        // Check that the correct layout is displayed.
        $ipe_placeholders_xpath = '//div[contains(@class, "panels-ipe-display-container")]' .
            '//div[contains(@class, "panels-ipe-placeholder")]';
        $this->waitUntilElementIsDisplayed($ipe_placeholders_xpath);
        $ipe_placeholders = $this->elements($this->using('xpath')->value($ipe_placeholders_xpath));

        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element $ipe_placeholder */
        foreach ($ipe_placeholders as $ipe_placeholder) {
            $this->assertTrue($ipe_placeholder->displayed());
        }

        $ipe_containers_xpath = '//div[contains(@class, "panels-ipe-display-container")]' .
            '//div[contains(@class, "paddle-layout-' . $random_layout . '")]';
        $this->waitUntilElementIsDisplayed($ipe_containers_xpath);
        $ipe_containers = $this->elements($this->using('xpath')->value($ipe_containers_xpath));

        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element $ipe_container */
        foreach ($ipe_containers as $ipe_container) {
            $this->assertTrue($ipe_container->displayed());
        }

        // Save the page so that subsequent tests are not greeted by an alert.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();
    }

    /**
     * Tests if the featured image is shown on an advanced search page.
     *
     * Based on the same method from AdvancedSearchTest.
     */
    public function testFeaturedImageShownOnAdvancedSearch()
    {
        // Clear nodes that might interfere with the test.
        $this->deleteExistingNodes();

        // Create an image atom to test with.
        $atom = $this->assetCreationService->createImage();
        $nid = $this->createNodeOrganizationalUnit();

        $this->nodeEditPage->go($nid);
        $this->nodeEditPage->featuredImage->selectAtom($atom['id']);
        $this->nodeEditPage->contextualToolbar->buttonSave->click();
        $this->adminNodeViewPage->checkArrival();

        $this->publishPage($nid);

        // Index the page and commit the search index.
        $this->drupalSearchApiApi->indexItems('node_index');
        $this->drupalSearchApiApi->commitIndex('node_index');

        // Create a maps node.
        $map_nid = $this->contentCreationService->createMapsPage();

        // Go to the frontend view.
        $this->frontendViewPage->go($map_nid);
        $results = $this->frontendViewPage->searchResultsPane->results->getResultsKeyedByTitle();

        $this->assertCount(1, $results);
        /** @var SearchResult $result */
        $result = array_shift($results);
        $this->assertNotEmpty($result->featuredImage);
    }
}
