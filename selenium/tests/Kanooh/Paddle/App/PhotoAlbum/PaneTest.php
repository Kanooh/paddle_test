<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\PhotoAlbum\PaneTest.
 */

namespace Kanooh\Paddle\App\PhotoAlbum;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Apps\PhotoAlbum;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage as AdministrativeNodeViewPage;
use Kanooh\Paddle\Pages\Element\Pane\PhotoAlbum\PhotoAlbumPane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\PhotoAlbum\PhotoAlbumPanelsContentType;
use Kanooh\Paddle\Pages\Element\Region\Region;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\TaxonomyService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Tests the Photo Album pane.
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class PaneTest extends WebDriverTestCase
{
    /**
     * @var AdministrativeNodeViewPage
     */
    protected $administrativeNodeViewPage;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var AssetCreationService
     */
    protected $assetCreationService;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var LayoutPage
     */
    protected $layoutPage;

    /**
     * @var TaxonomyService
     */
    protected $taxonomyService;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * @var ViewPage
     */
    protected $viewPage;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();
        $this->administrativeNodeViewPage = new AdministrativeNodeViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->assetCreationService = new AssetCreationService($this);
        $this->layoutPage = new LayoutPage($this);
        $this->taxonomyService = new TaxonomyService();
        $this->userSessionService = new UserSessionService($this);
        $this->viewPage = new ViewPage($this);

        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Go to the login page and log in as chief editor.
        $this->userSessionService->login('ChiefEditor');

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new PhotoAlbum());
    }

    /**
     * Tests the photo album pane.
     */
    public function testPane()
    {
        // Add a tag.
        $tag = $this->alphanumericTestDataProvider->getValidValue();
        $this->taxonomyService->createTerm(TaxonomyService::TAGS_VOCABULARY_ID, $tag);

        // Add a term.
        $term = $this->alphanumericTestDataProvider->getValidValue();
        $term_id = $this->taxonomyService->createTerm(TaxonomyService::GENERAL_TAGS_VOCABULARY_ID, $term);

        // Create 2 images. One linked to the tag, the other linked to the term.
        $tag_image = $this->assetCreationService->createImage(array('tags' => array($tag),));

        $term_image = $this->assetCreationService->createImage(array('general_terms' => array($term_id),));

        // Create a basic page and add a photo album pane with the tag field
        // filled out.
        $nid = $this->contentCreationService->createBasicPage();

        $this->layoutPage->go($nid);
        /** @var Region $region */
        $region = $this->layoutPage->display->getRandomRegion();
        $content_type = new PhotoAlbumPanelsContentType($this);

        $callable = new SerializableClosure(function () use ($content_type, $tag) {
            // Select a tag.
            $content_type->getForm()->filterTags->fill($tag);
        });

        $pane = $region->addPane($content_type, $callable);

        // Save the layout page.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Verify that only the image with tag is shown.
        $this->viewPage->go($nid);
        $album_pane = new PhotoAlbumPane($this, $pane->getUuid(), $pane->getXPathSelector());
        $this->assertArrayHasKey($tag_image['id'], $album_pane->images);
        $this->assertArrayNotHasKey($term_image['id'], $album_pane->images);

        // Edit the pane and set the term.
        $this->layoutPage->go($nid);
        $album_pane = new PhotoAlbumPane($this, $pane->getUuid(), $pane->getXPathSelector());

        $callable = new SerializableClosure(function () use ($album_pane, $term) {
            $album_pane->toolbar->buttonEdit->click();
            $album_pane->editPaneModal->waitUntilOpened();
            $form = $album_pane->contentType->getForm();

            $form->filterGeneralTags->fill($term);
            $form->filterTags->clear();

            $album_pane->editPaneModal->submit();
            $album_pane->editPaneModal->waitUntilClosed();
        });
        $album_pane->executeAndWaitUntilReloaded($callable);

        // Save the layout page.
        $this->layoutPage->contextualToolbar->buttonSave->click();
        $this->administrativeNodeViewPage->checkArrival();

        // Verify that only the image with term is shown.
        $this->viewPage->go($nid);
        $album_pane = new PhotoAlbumPane($this, $pane->getUuid(), $pane->getXPathSelector());
        $this->assertArrayNotHasKey($tag_image['id'], $album_pane->images);
        $this->assertArrayHasKey($term_image['id'], $album_pane->images);
    }
}
