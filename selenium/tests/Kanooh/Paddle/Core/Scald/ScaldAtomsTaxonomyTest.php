<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Scald\ScaldTest.php
 */

namespace Kanooh\Paddle\Core\Scald;

use Kanooh\Paddle\Pages\Admin\ContentManager\AssetsPage\AssetsPage;
use Kanooh\Paddle\Pages\Element\Scald\AddAssetModal;
use Kanooh\Paddle\Pages\Element\Scald\AddAtomModal;
use Kanooh\Paddle\Pages\Element\Scald\Document\AddOptionsModal as AddDocumentOptionsModal;
use Kanooh\Paddle\Pages\Element\Scald\Image\AddOptionsModal as AddImageOptionsModal;
use Kanooh\Paddle\Pages\Element\Scald\MovieFile\AddOptionsModal as AddVideoFileOptionsModal;
use Kanooh\Paddle\Pages\Element\Scald\MovieYoutube\AddModal;
use Kanooh\Paddle\Pages\Element\Scald\MovieYoutube\AddOptionsModal as AddVideoYoutubeOptionsModal;
use Kanooh\Paddle\Pages\Element\Scald\SourceModal;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\Paddle\Utilities\TaxonomyService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * ScaldAtomsTaxonomyTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ScaldAtomsTaxonomyTest extends WebDriverTestCase
{
    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AssetsPage
     */
    protected $assetsPage;

    /**
     * @var DrupalService
     */
    protected $drupalService;

    /**
     * @var TaxonomyService
     */
    protected $taxonomyService;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Create some instances to use later on.
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->assetsPage = new AssetsPage($this);
        $this->drupalService = new DrupalService();
        $this->taxonomyService = new TaxonomyService();
        $this->userSessionService = new UserSessionService($this);

        if (!$this->drupalService->isBootstrapped()) {
            $this->drupalService->bootstrap($this);
        }

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Tests taxonomy on file atoms.
     */
    public function testFileAtomTaxonomyFields()
    {
        // Create a term.
        $term = $this->alphanumericTestDataProvider->getValidValue();
        $tid = $this->taxonomyService->createTerm(TaxonomyService::GENERAL_TAGS_VOCABULARY_ID, $term);

        $this->assetsPage->go();
        $this->assetsPage->contextualToolbar->buttonAddNewAsset->click();

        $add_document_modal = new AddAssetModal($this);
        $add_document_modal->waitUntilOpened();

        // Test for a file atom.
        $add_document_modal->fileLink->click();
        // Upload a new document.
        $add_document_modal = new AddAtomModal($this);
        $add_document_modal->waitUntilOpened();

        $doc_path = dirname(__FILE__) . '/../../assets/pdf-sample.pdf';
        $title = basename($doc_path);
        $document = $this->file($doc_path);

        $add_document_modal->form->fileList->uploadFiles($document);

        $options_modal = new AddDocumentOptionsModal($this);
        $options_modal->waitUntilOpened();

        // Set a tag and a term.
        $tag = $this->alphanumericTestDataProvider->getValidValue();
        $options_modal->form->tags->value($tag);
        $options_modal->form->tagsAddButton->click();
        $options_modal->form->waitUntilTagIsDisplayed($tag);

        $options_modal->form->generalVocabularyTermReferenceTree->selectTerm($tid);
        $options_modal->form->finishButton->click();

        // Wait until the library is reloaded.
        $options_modal->waitUntilClosed();
        $this->waitUntilTextIsPresent('Atom ' . $title . ', of type File has been created.');

        // Get the newly created atom from the library, and click its edit link.
        $atom = $this->assetsPage->library->items[0];
        $atom->editLink->click();

        // Edit the atom and verify that the tag and term have been saved.
        $options_modal->waitUntilOpened();
        $options_modal->form->waitUntilTagIsDisplayed($tag);
        $term = $options_modal->form->generalVocabularyTermReferenceTree->getTermById($tid);
        $this->assertTrue($term->selected());

        $options_modal->close();
        $options_modal->waitUntilClosed();
        $this->assetsPage->checkArrival();
    }

    /**
     * Tests taxonomy on image atoms.
     */
    public function testImageAtomTaxonomyFields()
    {
        // Create a term.
        $term = $this->alphanumericTestDataProvider->getValidValue();
        $tid = $this->taxonomyService->createTerm(TaxonomyService::GENERAL_TAGS_VOCABULARY_ID, $term);

        $this->assetsPage->go();
        $this->assetsPage->contextualToolbar->buttonAddNewAsset->click();

        $add_image_modal = new AddAssetModal($this);
        $add_image_modal->waitUntilOpened();

        $add_image_modal->imageLink->click();
        $add_image_modal = new AddAtomModal($this);
        $add_image_modal->waitUntilOpened();

        $image_path = dirname(__FILE__) . '/../../assets/sample_image.jpg';
        $image = $this->file($image_path);
        $title = basename($image_path);

        $add_image_modal->form->fileList->uploadFiles($image);

        $options_modal = new AddImageOptionsModal($this);
        $options_modal->waitUntilOpened();

        // Set a tag and a term.
        $tag = $this->alphanumericTestDataProvider->getValidValue();
        $options_modal->form->tags->value($tag);
        $options_modal->form->tagsAddButton->click();
        $options_modal->form->waitUntilTagIsDisplayed($tag);

        $options_modal->form->generalVocabularyTermReferenceTree->selectTerm($tid);

        // Fill out required field.
        $options_modal->form->alternativeText->fill($this->alphanumericTestDataProvider->getValidValue());
        $options_modal->form->finishButton->click();

        // Wait until the library is reloaded.
        $options_modal->waitUntilClosed();
        $this->waitUntilTextIsPresent('Atom ' . $title . ', of type Image has been created.');

        // Get the newly created atom from the library, and click its edit link.
        $atom = $this->assetsPage->library->items[0];
        $atom->editLink->click();

        // Edit the atom and verify that the tag and term have been saved.
        $options_modal->waitUntilOpened();
        $options_modal->form->waitUntilTagIsDisplayed($tag);
        $term = $options_modal->form->generalVocabularyTermReferenceTree->getTermById($tid);
        $this->assertTrue($term->selected());

        $options_modal->close();
        $options_modal->waitUntilClosed();
        $this->assetsPage->checkArrival();
    }

    /**
     * Tests taxonomy on video atoms.
     */
    public function testVideoAtomTaxonomyFields()
    {
        // Create a term.
        $term = $this->alphanumericTestDataProvider->getValidValue();
        $tid = $this->taxonomyService->createTerm(TaxonomyService::GENERAL_TAGS_VOCABULARY_ID, $term);

        $this->assetsPage->go();
        $this->assetsPage->contextualToolbar->buttonAddNewAsset->click();

        $add_video_modal = new AddAssetModal($this);
        $add_video_modal->waitUntilOpened();

        $add_video_modal->videoLink->click();
        // Pick the appropriate source.
        $source_modal = new SourceModal($this);
        $source_modal->waitUntilOpened();
        $source_modal->chooseSource('paddle_scald_video_file');

        $add_modal = new AddAtomModal($this);
        $add_modal->waitUntilOpened();

        $video_path = dirname(__FILE__) . '/../../assets/sample_video.mp4';
        $video_file = $this->file($video_path);
        $title = basename($video_path);
        $add_modal->form->fileList->uploadFiles($video_file);

        $options_modal = new AddVideoFileOptionsModal($this);
        $options_modal->waitUntilOpened();

        // Set a tag and a term.
        $tag = $this->alphanumericTestDataProvider->getValidValue();
        $options_modal->form->tags->value($tag);
        $options_modal->form->tagsAddButton->click();
        $options_modal->form->waitUntilTagIsDisplayed($tag);

        $options_modal->form->generalVocabularyTermReferenceTree->selectTerm($tid);

        // Set the required fields.
        $options_modal->form->height->fill('400');
        $options_modal->form->width->fill('400');
        $options_modal->form->finishButton->click();

        // Wait until the library is reloaded.
        $options_modal->waitUntilClosed();
        $this->waitUntilTextIsPresent('Atom ' . $title . ', of type Video has been created.');

        // Get the newly created atom from the library, and click its edit link.
        $atom = $this->assetsPage->library->items[0];
        $atom->editLink->click();

        // Edit the atom and verify that the tag and term have been saved.
        $options_modal->waitUntilOpened();
        $options_modal->form->waitUntilTagIsDisplayed($tag);
        $term = $options_modal->form->generalVocabularyTermReferenceTree->getTermById($tid);
        $this->assertTrue($term->selected());

        $options_modal->close();
        $options_modal->waitUntilClosed();
        $this->assetsPage->checkArrival();
    }

    /**
     * Tests taxonomy on youtube atoms.
     */
    public function testYoutubeAtomTaxonomyFields()
    {
        // Create a term.
        $term = $this->alphanumericTestDataProvider->getValidValue();
        $tid = $this->taxonomyService->createTerm(TaxonomyService::GENERAL_TAGS_VOCABULARY_ID, $term);

        $this->assetsPage->go();
        $this->assetsPage->contextualToolbar->buttonAddNewAsset->click();

        $add_video_modal = new AddAssetModal($this);
        $add_video_modal->waitUntilOpened();

        $add_video_modal->videoLink->click();
        // Pick the appropriate source.
        $source_modal = new SourceModal($this);
        $source_modal->waitUntilOpened();
        $source_modal->chooseSource('paddle_scald_youtube');

        $add_modal = new AddModal($this);
        $add_modal->waitUntilOpened();

        $title = 'USUAL SUSPECTS - Fite Dem Back (LKJ)';
        $add_modal->form->url->fill('https://www.youtube.com/watch?v=aTMbHEoAktM');
        $add_modal->form->continueButton->click();

        $options_modal = new AddVideoYoutubeOptionsModal($this);
        $options_modal->waitUntilOpened();

        // Set a tag and a term.
        $tag = $this->alphanumericTestDataProvider->getValidValue();
        $options_modal->form->tags->value($tag);
        $options_modal->form->tagsAddButton->click();
        $options_modal->form->waitUntilTagIsDisplayed($tag);

        $options_modal->form->generalVocabularyTermReferenceTree->selectTerm($tid);
        $options_modal->form->finishButton->click();

        // Wait until the library is reloaded.
        $options_modal->waitUntilClosed();
        $this->waitUntilTextIsPresent('Atom ' . $title . ', of type Video has been created.');

        // Get the newly created atom from the library, and click its edit link.
        $atom = $this->assetsPage->library->items[0];
        $atom->editLink->click();

        // Edit the atom and verify that the tag and term have been saved.
        $options_modal->waitUntilOpened();
        $options_modal->form->waitUntilTagIsDisplayed($tag);
        $term = $options_modal->form->generalVocabularyTermReferenceTree->getTermById($tid);
        $this->assertTrue($term->selected());

        $options_modal->close();
        $options_modal->waitUntilClosed();
        $this->assetsPage->checkArrival();
    }
}
