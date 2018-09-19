<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\ImagePane\ReferenceTrackerPaneSectionsTest.
 */

namespace Kanooh\Paddle\Core\Pane\ImagePane;

use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneSectionsTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ImagePanelsContentType;

/**
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
        // Create an image atom.
        $this->atom = $this->assetCreationService->createImage();

        // Add this atom to the expected references.
        $this->additionalReferences['scald_atom'][] = $this->atom['id'];
    }

    /**
     * {@inheritDoc}
     */
    protected function getPaneContentTypeInstance()
    {
        return new ImagePanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type, $referenced_ids)
    {
        // Select the created atom.
        /* @var ImagePanelsContentType $content_type */
        $content_type->getForm()->image->selectButton->click();
        $this->scaldService->insertAtom($this->atom['id']);
    }
}
