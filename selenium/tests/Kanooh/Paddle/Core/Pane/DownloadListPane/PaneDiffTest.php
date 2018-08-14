<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\DownloadListPane\PaneDiffTest.
 */

namespace Kanooh\Paddle\Core\Pane\DownloadListPane;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Core\Pane\Base\PaneDiffTestBase;
use Kanooh\Paddle\Pages\Element\Pane\DownloadListPane;
use Kanooh\Paddle\Pages\Element\Pane\Pane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\DownloadList\DownloadsTableRow;
use Kanooh\Paddle\Pages\Element\PanelsContentType\DownloadListPanelsContentType;
use Kanooh\Paddle\Pages\Element\Region\Region;
use Kanooh\Paddle\Utilities\TaxonomyService;

/**
 * Class PaneDiffTest.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneDiffTest extends PaneDiffTestBase
{

    /**
     * Contains atom information for later usage.
     *
     * @var array
     */
    protected $data = array();

    /**
     * @var TaxonomyService
     */
    protected $taxonomyService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();
        $this->taxonomyService = new TaxonomyService();

        $image = $this->assetCreationService->createImage();
        $this->data['old'] = $image;
        $this->config['old'] = array(
            'Manually selected files',
            $image['title'],
        );

        $tag_name = $this->alphanumericTestDataProvider->getValidValue();
        $this->taxonomyService->createTerm(TaxonomyService::TAGS_VOCABULARY_ID, $tag_name);
        $term_name = $this->alphanumericTestDataProvider->getValidValue();
        $this->taxonomyService->createTerm(TaxonomyService::GENERAL_TAGS_VOCABULARY_ID, $term_name);

        $this->data['new']['tag'] = $tag_name;
        $this->data['new']['term'] = $term_name;
        $this->config['new'] = array(
            'List based on tags',
            $tag_name,
            $term_name,
            'Sorting: File size descending',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function addPaneToRegion(Region $region)
    {
        $content_type = new DownloadListPanelsContentType($this);
        $data = $this->data;
        $test_case = $this;

        $callable = new SerializableClosure(
            function () use ($test_case, $content_type, $data) {
                $content_type->getForm()->selectionType->manual->select();
                /* @var DownloadsTableRow $row */
                $row = end($content_type->getForm()->downloadsTable->rows);
                $row->atom->selectButton->click();

                $test_case->scaldService->insertAtom($data['old']['id']);
            }
        );

        return $region->addPane($content_type, $callable);
    }

    /**
     * {@inheritdoc}
     */
    public function editPane(Pane $pane)
    {
        $download_pane = new DownloadListPane($this, $pane->getUuid(), $pane->getXPathSelector());
        $data = $this->data;
        $test_case = $this;

        unset($this->config['old'][0]);

        $callable = new SerializableClosure(
            function () use ($test_case, $download_pane, $data) {
                $download_pane->toolbar->buttonEdit->click();
                $download_pane->editPaneModal->waitUntilOpened();
                $content_type = $download_pane->contentType;

                // Remove the image atom.
                $content_type->getForm()->downloadsTable->rows[0]->remove();

                // Add the tag, term and change the sorting.
                $content_type->getForm()->selectionType->tags->select();
                $content_type->getForm()->filterGeneralTags->waitUntilDisplayed();
                $content_type->getForm()->filterGeneralTags->fill($data['new']['term']);
                $content_type->getForm()->filterTags->fill($data['new']['tag']);
                $content_type->getForm()->sortFilesizeDesc->select();

                $download_pane->editPaneModal->submit();
                $download_pane->editPaneModal->waitUntilClosed();
            }
        );
        $download_pane->executeAndWaitUntilReloaded($callable);
    }

    /**
     * {@inheritdoc}
     */
    public function removePane(Pane $pane)
    {
        $download_pane = new DownloadListPane($this, $pane->getUuid(), $pane->getXPathSelector());

        $callable = new SerializableClosure(
            function () use ($download_pane) {
                $download_pane->delete();
            }
        );
        $download_pane->executeAndWaitUntilReloaded($callable);
    }
}
