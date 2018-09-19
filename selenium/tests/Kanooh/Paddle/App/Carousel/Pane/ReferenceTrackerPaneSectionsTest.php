<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Carousel\Pane\ReferenceTrackerPaneSectionsTest.
 */

namespace Kanooh\Paddle\App\Carousel\Pane;

use Kanooh\Paddle\Apps\Carousel;
use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneSectionsTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\Carousel\SlideForm;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CarouselPanelsContentType;

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
    public function setUpPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new Carousel);
    }

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
        return new CarouselPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type, $referenced_ids)
    {
        /* @var CarouselPanelsContentType $content_type */
        $content_type->addSlide();

        /* @var SlideForm $slide */
        $slide = array_shift($content_type->getForm()->slides);
        $slide->selectImage($this->atom['id']);
    }
}
