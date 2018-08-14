<?php
/**
* @file
* Contains \Kanooh\Paddle\App\Multilingual\Pane\MenuStructurePaneMultilingualTest.
*/

namespace Kanooh\Paddle\App\Multilingual\Pane;

use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\PanelsContentType\MenuStructurePanelsContentType;

/**
* Class MenuStructurePaneMultilingualTest
* @package Kanooh\Paddle\App\Multilingual\Pane
*
* @runTestsInSeparateProcesses
* @preserveGlobalState disabled
*/
class MenuStructurePaneMultilingualTest extends PaneMultilingualTestBase
{
    /**
     * Tests the multilingual functionality for menu structure panes.
     *
     * @group menuStructurePane
     */
    public function testMultilingual()
    {
        $landing_pages = array(
            'nl' => $this->contentCreationService->createLandingPage(),
            'fr' => $this->contentCreationService->createLandingPage(),
        );
        $this->contentCreationService->changeNodeLanguage($landing_pages['fr'], 'fr');
        foreach ($landing_pages as $lang_code => $nid) {
            $this->landingPagePanelsPage->go($nid);

            // Get the menus for the current language.
            $menus = paddle_menu_manager_get_menus($lang_code);

            // Add a pane to a random region.
            $region = $this->landingPagePanelsPage->display->getRandomRegion();
            $menu_structure_pane = new MenuStructurePanelsContentType($this);

            // Open the Add Pane dialog.
            $region->buttonAddPane->click();
            $modal = new AddPaneModal($this);
            $modal->waitUntilOpened();

            // Select the pane type in the modal dialog.
            $modal->selectContentType($menu_structure_pane);
            $options = $menu_structure_pane->menu->getOptions();

            $result = array_diff_key($options, $menus);
            $this->assertEmpty($result);

            // Close the modal and save the page so we don't block other tests.
            $modal->close();
            $this->landingPagePanelsPage->contextualToolbar->buttonSave->click();
            $this->administrativeNodeViewPage->checkArrival();
        }
    }
}
