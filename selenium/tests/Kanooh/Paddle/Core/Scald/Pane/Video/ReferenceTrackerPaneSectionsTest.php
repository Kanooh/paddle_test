<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Scald\Pane\Video\ReferenceTrackerPaneSectionsTest.
 */

namespace Kanooh\Paddle\Core\Scald\Pane\Video;

use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneSectionsTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\VideoPanelsContentType;

/**
 * Tests that the atom references in the sections of the video pane are picked up.
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ReferenceTrackerPaneSectionsTest extends ReferenceTrackerPaneSectionsTestBase
{

    /**
     * @var array
     *   The atom data that will be used for the test.
     */
    protected $atom;

    /**
     * {@inheritDoc}
     */
    protected function additionalTestSetUp()
    {
        // Create a video atom.
        $this->atom = $this->assetCreationService->createVideo();

        // Add this atom to the expected references.
        $this->additionalReferences['scald_atom'][] = $this->atom['id'];
    }

    /**
     * {@inheritDoc}
     */
    protected function getPaneContentTypeInstance()
    {
        return new VideoPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type, $referenced_ids)
    {
        // Select the created atom.
        /* @var VideoPanelsContentType $content_type */
        $content_type->getForm()->video->selectAtom($this->atom['id']);
    }
}
