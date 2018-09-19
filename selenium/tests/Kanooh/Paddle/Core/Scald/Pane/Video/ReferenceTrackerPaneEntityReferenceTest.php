<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Scald\Pane\Video\ReferenceTrackerPaneEntityReferenceTest.
 */

namespace Kanooh\Paddle\Core\Scald\Pane\Video;

use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneEntityReferenceTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\VideoPanelsContentType;

/**
 * Tests that the atom reference in the pane body of the video pane is picked up.
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ReferenceTrackerPaneEntityReferenceTest extends ReferenceTrackerPaneEntityReferenceTestBase
{

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();
    }

    /**
     * {@inheritdoc}
     */
    protected function setUpReferencedEntities()
    {
        // Create an video atom.
        $atom = $this->assetCreationService->createVideo();

        return array('scald_atom' => array($atom['id']));
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
    protected function configurePaneContentType($content_type, $references)
    {
        // Select the created atom.
        /* @var VideoPanelsContentType $content_type */
        $content_type->getForm()->video->selectAtom($references['scald_atom'][0]);
    }
}
