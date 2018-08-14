<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\DownloadListPane\ReferenceTrackerPaneEntityReferenceTest.
 */

namespace Kanooh\Paddle\Core\Pane\DownloadListPane;

use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneEntityReferenceTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\DownloadListPanelsContentType;
use Kanooh\Paddle\Pages\Element\Scald\LibraryModal;
use Kanooh\Paddle\Utilities\AssetCreationService;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ReferenceTrackerPaneEntityReferenceTest extends ReferenceTrackerPaneEntityReferenceTestBase
{

    /**
     * {@inheritDoc}
     */
    protected function setUpReferencedEntities()
    {
        $atoms = array(
            $this->assetCreationService->createImage(),
            $this->assetCreationService->createVideo(),
            $this->assetCreationService->createFile(),
            $this->assetCreationService->createYoutubeVideo(),
        );

        return array(
            'scald_atom' => array_column($atoms, 'id'),
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function getPaneContentTypeInstance()
    {
        return new DownloadListPanelsContentType($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see DownloadListPanelsContentType::fillInConfigurationForm()
     */
    protected function configurePaneContentType($content_type, $references)
    {
        /* @var DownloadListPanelsContentType $content_type */
        $rows = $content_type->getForm()->downloadsTable->rows;

        // Remove all existing files.
        foreach ($rows as $row) {
            $row->remove();
        }

        foreach ($references['scald_atom'] as $atom_id) {
            $content_type->getForm()->addRow();
            $row = end($content_type->getForm()->downloadsTable->rows);
            $row->atom->selectButton->click();

            $library_modal = new LibraryModal($this);
            $library_modal->waitUntilOpened();
            $atom = $library_modal->library->getAtomById($atom_id);
            $atom->insertLink->click();
            $library_modal->waitUntilClosed();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Remove atoms in this test, as youtube video will cause errors for
        // other tests.
        AssetCreationService::cleanUp($this);

        parent::tearDown();
    }
}
