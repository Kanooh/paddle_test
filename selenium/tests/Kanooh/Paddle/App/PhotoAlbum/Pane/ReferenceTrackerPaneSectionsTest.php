<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\PhotoAlbum\Pane\ReferenceTrackerPaneSectionsTest.
 */

namespace Kanooh\Paddle\App\PhotoAlbum\Pane;

use Kanooh\Paddle\Apps\PhotoAlbum;
use Kanooh\Paddle\Core\Pane\Base\ReferenceTrackerPaneSectionsTestBase;
use Kanooh\Paddle\Pages\Element\PanelsContentType\PhotoAlbum\PhotoAlbumPanelsContentType;
use Kanooh\Paddle\Utilities\TaxonomyService;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ReferenceTrackerPaneSectionsTest extends ReferenceTrackerPaneSectionsTestBase
{
    /**
     * @var string
     */
    protected $generalTerm;

    /**
     * @var string
     */
    protected $tag;

    /**
     * @var TaxonomyService
     */
    protected $taxonomyService;

    /**
     * {@inheritDoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        $this->appService->enableApp(new PhotoAlbum);
    }

    /**
     * {@inheritDoc}
     */
    protected function additionalTestSetUp()
    {
        $this->taxonomyService = new TaxonomyService();

        // Add a tag.
        $this->tag = $this->alphanumericTestDataProvider->getValidValue();
        $this->taxonomyService->createTerm(TaxonomyService::TAGS_VOCABULARY_ID, $this->tag);

        // Add a tag.
        $this->generalTerm = $this->alphanumericTestDataProvider->getValidValue();
        $this->taxonomyService->createTerm(TaxonomyService::GENERAL_TAGS_VOCABULARY_ID, $this->generalTerm);
    }

    /**
     * {@inheritDoc}
     */
    protected function getPaneContentTypeInstance()
    {
        return new PhotoAlbumPanelsContentType($this);
    }

    /**
     * {@inheritDoc}
     */
    protected function configurePaneContentType($content_type, $referenced_ids)
    {
        // Select a tag and a term.
        /* @var PhotoAlbumPanelsContentType $content_type */
        $content_type->getForm()->filterGeneralTags->fill($this->generalTerm);
        $content_type->getForm()->filterTags->fill($this->tag);
    }
}
