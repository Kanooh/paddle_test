<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Pane\ImagePane\PaneLinkTest.
 */

namespace Kanooh\Paddle\Core\Pane\ImagePane;

use Kanooh\Paddle\Core\Pane\Base\PaneLinkTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ImagePanelsContentType;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\ScaldService;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneLinkTest extends PaneLinkTestBase
{
    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * @var array
     *   The atom data that will be used for the test.
     */
    protected $atom;

    /**
     * @var ScaldService
     */
    protected $scaldService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();
        $this->assetCreationService = new AssetCreationService($this);
        $this->scaldService = new ScaldService($this);

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
    protected function configurePaneContentType($content_type, $referenced_ids)
    {
        // Select the created atom.
        /* @var ImagePanelsContentType $content_type */
        $content_type->getForm()->image->selectButton->click();
        $this->scaldService->insertAtom($this->atom['id']);
    }
}
