<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\ImagePane\PaneSectionsDiffTest.
 */

namespace Kanooh\Paddle\Core\Pane\ImagePane;

use Kanooh\Paddle\Core\Pane\Base\PaneSectionsDiffTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ImagePanelsContentType;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneSectionsDiffTest extends PaneSectionsDiffTestBase
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
    protected function configurePaneContentType($content_type)
    {
        // Select the created atom.
        /* @var ImagePanelsContentType $content_type */
        $content_type->getForm()->image->selectButton->click();
        $this->scaldService->insertAtom($this->atom['id']);
    }
}
