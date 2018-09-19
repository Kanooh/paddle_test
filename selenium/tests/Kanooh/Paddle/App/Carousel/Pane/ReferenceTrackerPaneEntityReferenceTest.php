<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\Carousel\Pane\ReferenceTrackerPaneEntityReferenceTest.
 */

namespace Kanooh\Paddle\App\Carousel\Pane;

use Kanooh\Paddle\Apps\Carousel;
use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneEntityReferenceTestBase;
use Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete;
use Kanooh\Paddle\Pages\Element\PanelsContentType\Carousel\SlideForm;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CarouselPanelsContentType;

/**
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

        $this->appService->enableApp(new Carousel);
    }

    /**
     * {@inheritDoc}
     */
    protected function setUpReferencedEntities()
    {
        $references = array();

        for ($i = 0; $i < 3; $i++) {
            $atom = $this->assetCreationService->createImage();
            $references['scald_atom'][] = $atom['id'];
            $references['node'][] = $this->contentCreationService->createBasicPage();
        }

        return $references;
    }

    /**
     * {@inheritDoc}
     */
    protected function getPaneContentTypeInstance()
    {
        return new CarouselPanelsContentType($this);
    }

    /**
     * {@inheritdoc}
     */
    protected function configurePaneContentType($content_type, $references)
    {
        /* @var CarouselPanelsContentType $content_type */
        for ($i = 0; $i < 3; $i++) {
            $content_type->addSlide();

            /* @var SlideForm[] $slides */
            $slides = array_values($content_type->getForm()->slides);
            $slides[$i]->selectImage($references['scald_atom'][$i]);
            $slides[$i]->urlTypeInternalLink->select();
            $slides[$i]->internalUrl->fill('node/' . $references['node'][$i]);
            // Pick the suggestion.
            $autocomplete = new AutoComplete($this);
            $autocomplete->waitUntilDisplayed();
            $autocomplete->pickSuggestionByPosition();
        }
    }
}
