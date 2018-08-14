<?php
/**
* @file
* Contains \Kanooh\Paddle\App\Multilingual\Pane\CalendarPaneMultilingualTest.
*/

namespace Kanooh\Paddle\App\Multilingual\Pane;

use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CalendarPanelsContentType;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;

/**
* Class CalendarPaneMultilingualTest
* @package Kanooh\Paddle\App\Multilingual\Pane
*
* @runTestsInSeparateProcesses
* @preserveGlobalState disabled
*/
class CalendarPaneMultilingualTest extends PaneMultilingualTestBase
{
    /**
     * @var EditPage
     */
    protected $editPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->editPage = new EditPage($this);
    }

    /**
     * Tests the multilingual functionality for calendar panes.
     *
     * @group calendarPane
     */
    public function testMultilingual()
    {
        // Delete all calendar item pages so we are sure that we get the wanted nodes in the select options.
        $this->cleanUpService->deleteEntities('node', 'calendar_item');

        // Create a French and a Dutch page.
        $data = array(
            'fr' => array(
                'title' => $this->alphanumericTestDataProvider->getValidValue(),
                'tag_name' => $this->alphanumericTestDataProvider->getValidValue(),
            ),
            'nl' => array(
                'title' => $this->alphanumericTestDataProvider->getValidValue(),
                'tag_name' => $this->alphanumericTestDataProvider->getValidValue(),
            ),
        );
        foreach ($data as $lang_code => $item) {
            $data[$lang_code]['nid'] = $this->contentCreationService->createCalendarItem($item['title']);

            // Add a tag to the node.
            $this->editPage->go($data[$lang_code]['nid']);
            $this->editPage->language->selectOptionByValue($lang_code);
            $this->editPage->tags->clear();
            $this->editPage->tags->value($item['tag_name']);
            $this->editPage->tagsAddButton->click();
            $this->editPage->waitUntilTagIsDisplayed(ucfirst($item['tag_name']));

            // Save and publish the page.
            $this->editPage->contextualToolbar->buttonSave->click();
            $this->administrativeNodeViewPage->checkArrival();
            $this->administrativeNodeViewPage->contextualToolbar->buttonPublish->click();
            $this->administrativeNodeViewPage->checkArrival();
        }

        // Create a Dutch and French landing pages to add the calendar panes to.
        $landing_pages = array(
            'nl' => $this->contentCreationService->createLandingPage(),
            'fr' => $this->contentCreationService->createLandingPage(),
        );
        $this->contentCreationService->changeNodeLanguage($landing_pages['fr'], 'fr');
        foreach ($landing_pages as $lang_code => $nid) {
            $this->landingPagePanelsPage->go($nid);

            // Add a pane to a random region.
            $region = $this->landingPagePanelsPage->display->getRandomRegion();
            $calendar_pane = new CalendarPanelsContentType($this);

            // Open the Add Pane dialog.
            $region->buttonAddPane->click();
            $modal = new AddPaneModal($this);
            $modal->waitUntilOpened();

            // Select the pane type in the modal dialog.
            $modal->selectContentType($calendar_pane);
            $options = $calendar_pane->getForm()->calendarTags->getAll();

            $values = array();
            foreach ($options as $option) {
                $term = taxonomy_term_load($option->getValue());
                $values[] = strtolower($term->name);
            }

            $this->assertTrue(in_array(strtolower($data[$lang_code]['tag_name']), $values));

            // Close the modal and save the page so we don't block other tests.
            $modal->close();
            $this->landingPagePanelsPage->contextualToolbar->buttonSave->click();
            $this->administrativeNodeViewPage->checkArrival();
        }
    }
}
