<?php
/**
* @file
* Contains \Kanooh\Paddle\App\Multilingual\Pane\SimpleContactPaneMultilingualTest.
*/

namespace Kanooh\Paddle\App\Multilingual\Pane;

use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\PanelsContentType\SimpleContactFormPanelsContentType;

/**
* Class SimpleContactPaneMultilingualTest
* @package Kanooh\Paddle\App\Multilingual\Pane
*
* @runTestsInSeparateProcesses
* @preserveGlobalState disabled
*/
class SimpleContactPaneMultilingualTest extends PaneMultilingualTestBase
{
    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();
    }

    /**
     * Tests the multilingual functionality for simple contact unit panes.
     *
     * @group SimpleContactPane
     */
    public function testMultilingual()
    {
        // Delete all contact pages so we are sure that we get the wanted nodes in the select options.
        $this->cleanUpService->deleteEntities('node', 'simple_contact_page');

        // Create a French and a Dutch page.
        $data = array(
            'fr' => array( 'title' => $this->alphanumericTestDataProvider->getValidValue()),
            'nl' => array('title' => $this->alphanumericTestDataProvider->getValidValue()),
        );
        foreach ($data as $lang_code => $item) {
            $data[$lang_code]['nid'] = $this->contentCreationService->createSimpleContact($item['title']);
            $this->contentCreationService->changeNodeLanguage($data[$lang_code]['nid'], $lang_code);

            // Save the page.
            $this->administrativeNodeViewPage->go($data[$lang_code]['nid']);
            $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
            $this->administrativeNodeViewPage->checkArrival();
        }

        // Create a Dutch and French landing pages to add the simple contact pane to.
        $landing_pages = array(
            'nl' => $this->contentCreationService->createLandingPage(),
            'fr' => $this->contentCreationService->createLandingPage(),
        );
        $this->contentCreationService->changeNodeLanguage($landing_pages['fr'], 'fr');
        foreach ($landing_pages as $lang_code => $nid) {
            $this->landingPagePanelsPage->go($nid);

            // Add a pane to a random region.
            $region = $this->landingPagePanelsPage->display->getRandomRegion();
            $simple_contact_pane = new SimpleContactFormPanelsContentType($this);

            // Open the Add Pane dialog.
            $region->buttonAddPane->click();
            $modal = new AddPaneModal($this);
            $modal->waitUntilOpened();

            // Select the pane type in the modal dialog.
            $modal->selectContentType($simple_contact_pane);
            $options = $simple_contact_pane->form->node->getOptions();
            foreach ($data as $code => $item) {
                if ($lang_code == $code) {
                    $this->assertNotEmpty($options[$item['nid']]);
                } else {
                    $this->assertEmpty($options[$item['nid']]);
                }
            }

            // Close the modal and save the page so we don't block other tests.
            $modal->close();
            $this->landingPagePanelsPage->contextualToolbar->buttonSave->click();
            $this->administrativeNodeViewPage->checkArrival();
        }
    }
}
