<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\PaddleDownloadListPaneTest.
 */

namespace Kanooh\Paddle\Core\Pane;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\Carousel;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdminViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Element\Pane\DownloadListPane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\DownloadList\DownloadsTableRow;
use Kanooh\Paddle\Pages\Element\PanelsContentType\DownloadListPanelsContentType;
use Kanooh\Paddle\Pages\Element\Scald\AtomFieldAtom;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\TaxonomyService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class DownloadListPaneTest extends WebDriverTestCase
{
    /**
     * Administrative node view page.
     *
     * @var AdminViewPage
     */
    protected $adminViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * Associative array of atoms, keyed by their type.
     *
     * @var array
     */
    protected $atoms;

    /**
     * @var CleanUpService
     */
    protected $cleanUpService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * Landing page layout page.
     *
     * @var PanelsContentPage
     */
    protected $layoutPage;

    /**
     * @var TaxonomyService
     */
    protected $taxonomyService;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * Node front-end view page.
     *
     * @var ViewPage
     */
    protected $viewPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->adminViewPage = new AdminViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->assetCreationService = new AssetCreationService($this);
        $this->cleanUpService = new CleanUpService($this);
        $this->layoutPage = new PanelsContentPage($this);
        $this->taxonomyService = new TaxonomyService();
        $this->userSessionService = new UserSessionService($this);
        $this->viewPage = new ViewPage($this);

        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Tests the configuration for the manual selection mode.
     *
     * @group scald
     * @group panes
     */
    public function testManualSelectionConfiguration()
    {
        $pane = $this->createManualSelectionTestPane();
        $pane_uuid = $pane->getUuid();
        $pane_xpath = $pane->getXPathSelector();

        $download_pane = new DownloadListPane($this, $pane_uuid, $pane_xpath);
        $downloads = $download_pane->downloads;
        $urls = array_keys($downloads);

        // Don't put the extension at the end of the filename, as the uploaded
        // files might get a number appended to their filename if they have
        // been uploaded before.
        // Don't start the folder structure higher than 'files' because not all
        // sites (eg. multi-site subsites) use 'sites/default/' to put their
        // files directory in.
        $image_url = 'files/thumbnails/image/' .
            pathinfo($this->atoms['image']['path'], PATHINFO_FILENAME);

        $file_url = 'files/atoms/files/' .
            pathinfo($this->atoms['file']['path'], PATHINFO_FILENAME);

        $video_url = 'files/atoms/video/' .
            pathinfo($this->atoms['video']['path'], PATHINFO_FILENAME);

        $expected_downloads = array(
            $image_url => $this->atoms['image']['title'],
            $file_url => $this->atoms['file']['title'],
            $video_url => $this->atoms['video']['title'],
            'youtube.com/watch?v=dQw4w9WgXcQ' => $this->atoms['youtube']['title'],
        );
        $expected_urls = array_keys($expected_downloads);

        for ($i = 0; $i < count($expected_urls); $i++) {
            // Make sure the url contains the expected url part.
            $expected_url = $expected_urls[$i];
            $url = $urls[$i];
            $this->assertContains($expected_url, $url);

            // Make sure the download titles are the same.
            $expected_title = $expected_downloads[$expected_url];
            $title = $downloads[$url];
            $this->assertContains($expected_title, $title);
        }
    }

    /**
     * Test the remove row functionality.
     *
     * @group scald
     * @group panes
     */
    public function testManualSelectionRowRemoval()
    {
        $pane = $this->createManualSelectionTestPane();
        $pane_uuid = $pane->getUuid();
        $pane_xpath = $pane->getXPathSelector();

        // Test the sorting behavior.
        $callable = new SerializableClosure(
            function ($content_type) {
                // Remove first and third row.
                /* @var DownloadListPanelsContentType $content_type */
                $content_type->getForm()->downloadsTable->rows[2]->remove();
                $content_type->getForm()->downloadsTable->rows[0]->remove();
            }
        );
        $this->editPaneAndWaitUntilReloaded($pane, $callable);

        // Get the rendered pane.
        $download_pane = new DownloadListPane($this, $pane_uuid, $pane_xpath);

        // Prepare the list of names in the order we expect.
        $expected_titles = array(
            $this->atoms['file']['title'],
            $this->atoms['youtube']['title'],
        );

        // Verify that the list is in the expected order.
        $this->compareAtomsValues($expected_titles, array_values($download_pane->downloads));
        // Edit the pane again.
        $pane->toolbar->buttonEdit->click();
        $pane->editPaneModal->waitUntilOpened();
        $content_type = new DownloadListPanelsContentType($this);

        // Remove all the rows.
        $content_type->getForm()->downloadsTable->rows[1]->remove();
        $content_type->getForm()->downloadsTable->rows[0]->remove();

        // Verify that an error message is shown when trying to save an empty
        // pane.
        $modal = $pane->editPaneModal;
        $modal->submit();
        $this->waitUntilTextIsPresent('You need to select at least one file.');
        $modal->close();
    }

    /**
     * Test the sorting of atoms in the download list.
     *
     * @group scald
     * @group panes
     */
    public function testManualSelectionSorting()
    {
        $pane = $this->createManualSelectionTestPane();
        $pane_uuid = $pane->getUuid();
        $pane_xpath = $pane->getXPathSelector();

        // Test the sorting behavior.
        $webdriver = $this;
        $callable = new SerializableClosure(
            function ($content_type) use ($webdriver) {
                // Verify that all the atoms are present in the original order.
                /* @var DownloadListPanelsContentType $content_type */
                $rows = $content_type->getForm()->downloadsTable->rows;
                $atoms = array_values($webdriver->atoms);
                foreach ($atoms as $index => $atom) {
                    $webdriver->assertEquals($atom['id'], $rows[$index]->atom->valueField->value());
                }

                // Move the last element to the top, so all elements get a new position.
                $content_type->getForm()->downloadsTable->dragRow(3, 0);
            }
        );
        $this->editPaneAndWaitUntilReloaded($pane, $callable);

        // Get the rendered pane.
        $download_pane = new DownloadListPane($this, $pane_uuid, $pane_xpath);

        // Prepare the list of names in the order we expect.
        $expected_titles = array(
            $this->atoms['youtube']['title'],
            $this->atoms['image']['title'],
            $this->atoms['file']['title'],
            $this->atoms['video']['title'],
        );

        // Verify that the list is in the expected order.
        $this->compareAtomsValues($expected_titles, array_values($download_pane->downloads));

        // Test that sorting works with rows removal.
        $callable = new SerializableClosure(
            function ($content_type) use ($webdriver) {
                // Drag the last row in the first spot.
                /* @var DownloadListPanelsContentType $content_type */
                $content_type->getForm()->downloadsTable->dragRow(3, 0);

                // Remove the second row.
                $content_type->getForm()->downloadsTable->rows[1]->remove();
            }
        );
        $this->editPaneAndWaitUntilReloaded($pane, $callable);

        // Prepare the list of names in the order we expect.
        $expected_titles = array(
            $this->atoms['video']['title'],
            $this->atoms['image']['title'],
            $this->atoms['file']['title'],
        );

        // Verify that the list is in the expected order.
        $this->compareAtomsValues($expected_titles, array_values($download_pane->downloads));
    }

    /**
     * Tests that the atom titles are displayed on the pane edit form.
     * There is a CSS coming from Paddle Carousel which was hiding the atom
     * titles in the download pane.
     *
     * @group scald
     * @group regression
     *
     * @see https://one-agency.atlassian.net/browse/KANWEBS-3624
     */
    public function testAtomTitlesPresent()
    {
        // First enable Paddle Carousel since this causes the problem to manifest.
        $app_service = new AppService($this, $this->userSessionService);
        $app_service->enableApp(new Carousel);

        // Now add a download pane.
        $pane = $this->createManualSelectionTestPane();
        $pane->toolbar->buttonEdit->click();
        $pane->editPaneModal->waitUntilOpened();
        $content_type = new DownloadListPanelsContentType($this);

        // Check that the titles are present.
        /** @var DownloadsTableRow[] $rows */
        $rows = $content_type->getForm()->downloadsTable->rows;
        $atoms = array_values($this->atoms);
        foreach ($atoms as $index => $atom) {
            /** @var AtomFieldAtom[] $field_atoms */
            $field_atoms = $rows[$index]->atom->atoms;
            $this->assertEquals($atom['title'], $field_atoms[0]->title);
        }

        // Close the modal and save the page.
        $pane->editPaneModal->submit();
        $pane->editPaneModal->waitUntilClosed();
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->adminViewPage->checkArrival();
    }

    /**
     * Tests the configuration for the tags selection mode.
     *
     * @group scald
     * @group panes
     */
    public function testTagsSelectionConfiguration()
    {
        // Cleans up atoms created by other tests.
        $this->cleanUpService->deleteEntities('taxonomy_term');

        // Create a general vocabulary term.
        $general_title = $this->alphanumericTestDataProvider->getValidValue();
        $general_tid = $this->taxonomyService->createTerm(TaxonomyService::GENERAL_TAGS_VOCABULARY_ID, $general_title);

        // And a tag term.
        $tag_title = $this->alphanumericTestDataProvider->getValidValue();
        $this->taxonomyService->createTerm(TaxonomyService::TAGS_VOCABULARY_ID, $tag_title);

        $atoms = array(
            'image' => $this->assetCreationService->createImage(array(
                'title' => 'f' . $this->alphanumericTestDataProvider->getValidValue(),
                'general_terms' => array($general_tid),
            )),
            'file' => $this->assetCreationService->createFile(array(
                'title' => 'p' . $this->alphanumericTestDataProvider->getValidValue(),
                'tags' => array($tag_title),
            )),
            'video' => $this->assetCreationService->createVideo(array(
                'title' => 'm' . $this->alphanumericTestDataProvider->getValidValue(),
                'general_terms' => array($general_tid),
                'tags' => array($tag_title),
            )),
            'youtube' => $this->assetCreationService->createYoutubeVideo(array(
                'title' => 'a' . $this->alphanumericTestDataProvider->getValidValue(),
            )),
        );

        // Create a new landing page.
        $landing_page = $this->contentCreationService->createLandingPage();

        // Go to the layout page.
        $this->adminViewPage->go($landing_page);
        $this->adminViewPage->contextualToolbar->buttonPageLayout->click();
        $this->layoutPage->checkArrival();

        // Prepare the configuration callback.
        $content_type = new DownloadListPanelsContentType($this);
        $callable = new SerializableClosure(
            function () use ($content_type, $general_title) {
                $content_type->getForm()->selectionType->select('tags');

                // Select the general tag created before.
                $content_type->getForm()->filterGeneralTags->fill($general_title);
            }
        );

        // Create the pane and add it to a random region.
        $content_type = new DownloadListPanelsContentType($this);
        $region = $this->layoutPage->display->getRandomRegion();
        $pane = $region->addPane($content_type, $callable);

        // Get the rendered pane.
        $download_pane = new DownloadListPane($this, $pane->getUuid(), $pane->getXPathSelector());

        // Verify that only the two wanted atoms are rendered.
        $this->compareAtomsValues(
            array(
                $atoms['image']['title'],
                $atoms['video']['title'],
            ),
            array_values($download_pane->downloads)
        );

        // Remove the general term and add the tag.
        $callable = new SerializableClosure(
            function ($content_type) use ($tag_title) {
                /* @var DownloadListPanelsContentType $content_type */
                $content_type->getForm()->filterGeneralTags->clear();
                $content_type->getForm()->filterTags->fill($tag_title);
            }
        );
        $this->editPaneAndWaitUntilReloaded($pane, $callable);

        // Verify that only the two wanted atoms are rendered.
        $this->compareAtomsValues(
            array(
                $atoms['video']['title'],
                $atoms['file']['title'],
            ),
            array_values($download_pane->downloads)
        );

        // Now add also the general term in the filters.
        $callable = new SerializableClosure(
            function ($content_type) use ($general_title) {
                /* @var DownloadListPanelsContentType $content_type */
                $content_type->getForm()->filterGeneralTags->fill($general_title);
            }
        );
        $this->editPaneAndWaitUntilReloaded($pane, $callable);

        // Verify that only the atom tagged by both is rendered.
        $this->compareAtomsValues(
            array(
                $atoms['video']['title'],
            ),
            array_values($download_pane->downloads)
        );

        // Now remove all the filters.
        $callable = new SerializableClosure(
            function ($content_type) {
                /* @var DownloadListPanelsContentType $content_type */
                $content_type->getForm()->filterGeneralTags->clear();
                $content_type->getForm()->filterTags->clear();
            }
        );
        $this->editPaneAndWaitUntilReloaded($pane, $callable);

        // Verify that all system atoms are rendered too, when no filter is
        // specified.
        $atom_count = count(entity_load('scald_atom'));
        $this->assertCount($atom_count, $download_pane->downloads);
    }

    /**
     * Tests the sorting for the tags selection mode.
     *
     * @group scald
     * @group panes
     */
    public function testTagsSelectionSorting()
    {
        // Create a general vocabulary term to easily filter out all current
        // atoms, as by default in this selection mode the pane will show all
        // atoms in the system.
        $general_title = $this->alphanumericTestDataProvider->getValidValue();
        $general_tid = $this->taxonomyService->createTerm(TaxonomyService::GENERAL_TAGS_VOCABULARY_ID, $general_title);

        // Create the atoms
        $atoms = array(
            // File size is 64KB.
            'image' => $this->assetCreationService->createImage(array(
                'title' => 'a' . $this->alphanumericTestDataProvider->getValidValue(),
                'general_terms' => array($general_tid),
            )),
            // File size is 7.8KB.
            'file' => $this->assetCreationService->createFile(array(
                'title' => 'z' . $this->alphanumericTestDataProvider->getValidValue(),
                'general_terms' => array($general_tid),
            )),
            // File size is 375KB.
            'video' => $this->assetCreationService->createVideo(array(
                'title' => 'p' . $this->alphanumericTestDataProvider->getValidValue(),
                'general_terms' => array($general_tid),
            )),
            // No size for remote Youtube files.
            'youtube' => $this->assetCreationService->createYoutubeVideo(array(
                'title' => 'm' . $this->alphanumericTestDataProvider->getValidValue(),
                'general_terms' => array($general_tid),
            )),
        );

        // Create a new landing page.
        $landing_page = $this->contentCreationService->createLandingPage();

        // Go to the layout page.
        $this->adminViewPage->go($landing_page);
        $this->adminViewPage->contextualToolbar->buttonPageLayout->click();
        $this->layoutPage->checkArrival();

        // Prepare the configuration callback.
        $content_type = new DownloadListPanelsContentType($this);
        $callable = new SerializableClosure(
            function () use ($content_type, $general_title) {
                $content_type->getForm()->selectionType->select('tags');

                // Select the general tag created before.
                $content_type->getForm()->filterGeneralTags->fill($general_title);
            }
        );

        // Create the pane and add it to a random region.
        $content_type = new DownloadListPanelsContentType($this);
        $region = $this->layoutPage->display->getRandomRegion();
        $pane = $region->addPane($content_type, $callable);

        // Get the rendered pane.
        $download_pane = new DownloadListPane($this, $pane->getUuid(), $pane->getXPathSelector());

        // By default, the atoms should be shown in alphabetical order.
        $expected_titles = array(
            $atoms['image']['title'],
            $atoms['youtube']['title'],
            $atoms['video']['title'],
            $atoms['file']['title'],
        );
        $this->compareAtomsValues(
            $expected_titles,
            array_values($download_pane->downloads)
        );

        // Change the sorting to alphabetical descending.
        $callable = new SerializableClosure(
            function ($content_type) {
                /* @var DownloadListPanelsContentType $content_type */
                $content_type->getForm()->sortAlphabeticalDesc->select();
            }
        );
        $this->editPaneAndWaitUntilReloaded($pane, $callable);

        // Expect the previous titles in reverted order.
        $this->compareAtomsValues(
            array_reverse($expected_titles),
            array_values($download_pane->downloads)
        );

        // Change the sorting to file size ascending.
        $callable = new SerializableClosure(
            function ($content_type) {
                /* @var DownloadListPanelsContentType $content_type */
                $content_type->getForm()->sortFilesizeAsc->select();
            }
        );
        $this->editPaneAndWaitUntilReloaded($pane, $callable);

        // Verify that the files are ordered properly.
        $expected_titles = array(
            $atoms['youtube']['title'],
            $atoms['file']['title'],
            $atoms['image']['title'],
            $atoms['video']['title'],
        );
        $this->compareAtomsValues(
            $expected_titles,
            array_values($download_pane->downloads)
        );

        // Change again the sorting to file size descending.
        $callable = new SerializableClosure(
            function ($content_type) {
                /* @var DownloadListPanelsContentType $content_type */
                $content_type->getForm()->sortFilesizeDesc->select();
            }
        );
        $this->editPaneAndWaitUntilReloaded($pane, $callable);

        // Expect the previous titles in reverted order.
        $this->compareAtomsValues(
            array_reverse($expected_titles),
            array_values($download_pane->downloads)
        );
    }

    /**
     * Creates a download list pane for test purposes.
     *
     * @return \Kanooh\Paddle\Pages\Element\Pane\Pane
     */
    protected function createManualSelectionTestPane()
    {
        $this->atoms = array(
            'image' => $this->assetCreationService->createImage(),
            'file' => $this->assetCreationService->createFile(),
            'video' => $this->assetCreationService->createVideo(),
            'youtube' => $this->assetCreationService->createYoutubeVideo(),
        );

        // Create a new landing page.
        $landing_page = $this->contentCreationService->createLandingPage();

        // Go to the layout page.
        $this->adminViewPage->go($landing_page);
        $this->adminViewPage->contextualToolbar->buttonPageLayout->click();
        $this->layoutPage->checkArrival();

        // Create a new download list pane and prepare the atom ids to be
        // added when the pane is assigned to a region.
        $atom_ids = array();
        foreach ($this->atoms as $atom) {
            $atom_ids[] = $atom['id'];
        }
        $download_list_type = new DownloadListPanelsContentType($this);
        $download_list_type->atomIds = $atom_ids;

        // Add the pane to a random page region.
        $region = $this->layoutPage->display->getRandomRegion();
        $pane = $region->addPane($download_list_type);

        return $pane;
    }

    /**
     * Helper function to edit an existing pane configuration.
     *
     * @param \Kanooh\Paddle\Pages\Element\Pane\Pane $pane
     *   The pane object we are editing.
     * @param $callback
     *   The callback to execute.
     */
    protected function editPaneAndWaitUntilReloaded($pane, $callback)
    {
        $webdriver = $this;

        $callable = new SerializableClosure(
            function () use ($webdriver, $pane, $callback) {
                // Edit the pane again.
                $pane->toolbar->buttonEdit->click();
                $pane->editPaneModal->waitUntilOpened();
                $content_type = new DownloadListPanelsContentType($webdriver);

                $callback($content_type, $webdriver);

                // Close the modal.
                $modal = $pane->editPaneModal;
                $modal->submit();
                $modal->waitUntilClosed();
            }
        );
        $pane->executeAndWaitUntilReloaded($callable);
    }

    /**
     * Helper method to make sure the files lists are what we expect them to be.
     *
     * @param array $expected
     *  Array containing the files we expect to get in the pane.
     * @param array $actual
     *  Array containing the actual files rendered in the pane.
     *
     */
    protected function compareAtomsValues(array $expected, array $actual)
    {
        foreach ($expected as $key => $value) {
            $this->assertContains($value, $actual[$key]);
        }
    }

    /**
     * Tear down method. Removes the youtube video so we can add it again later.
     */
    public function tearDown()
    {
        AssetCreationService::cleanUp($this);
        parent::tearDown();
    }
}
