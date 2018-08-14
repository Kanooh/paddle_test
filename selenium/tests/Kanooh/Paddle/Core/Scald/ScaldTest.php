<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Core\Scald\ScaldTest.php
 */

namespace Kanooh\Paddle\Core\Scald;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\AddPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\AssetsPage\AssetsPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPage;
use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPage;
use Kanooh\Paddle\Pages\Admin\DashboardPage\DashboardPage;
use Kanooh\Paddle\Pages\Admin\Structure\MenuManager\MenuOverviewPage\MenuOverviewPage;
use Kanooh\Paddle\Pages\Admin\Structure\TaxonomyManager\OverviewPage\OverviewPage;
use Kanooh\Paddle\Pages\Element\Layout\Paddle2Col3to9Layout;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\Modal\EditPaneModal;
use Kanooh\Paddle\Pages\Element\Pane\ImagePane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\Image\ConfigurationForm;
use Kanooh\Paddle\Pages\Element\PanelsContentType\ImagePanelsContentType;
use Kanooh\Paddle\Pages\Element\Scald\AddAssetModal;
use Kanooh\Paddle\Pages\Element\Scald\AddAtomModal;
use Kanooh\Paddle\Pages\Element\Scald\DeleteModal;
use Kanooh\Paddle\Pages\Element\Scald\Document\AddOptionsModal as AddDocumentOptionsModal;
use Kanooh\Paddle\Pages\Element\Scald\Image\AddOptionsModal;
use Kanooh\Paddle\Pages\Element\Scald\LibraryModal;
use Kanooh\Paddle\Pages\Element\Scald\MovieFile\AddOptionsModal as AddVideoFileOptionsModal;
use Kanooh\Paddle\Pages\Element\Scald\MovieYoutube\AddModal;
use Kanooh\Paddle\Pages\Element\Scald\MovieYoutube\AddOptionsModal as AddVideoYoutubeOptionsModal;
use Kanooh\Paddle\Pages\Element\Scald\SourceModal;
use Kanooh\Paddle\Pages\Element\Wysiwyg\ImagePropertiesModalAdvancedForm;
use Kanooh\Paddle\Pages\Node\EditPage\EditPage;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage as NodeViewPage;
use Kanooh\Paddle\Utilities\AssetCreationService;
use Kanooh\Paddle\Utilities\CleanUpService;
use Kanooh\Paddle\Utilities\ContentCreationService;
use Kanooh\Paddle\Utilities\DrupalApi\DrupalAtomApi;
use Kanooh\Paddle\Utilities\DrupalApi\DrupalSearchApiApi;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * ScaldTest class.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ScaldTest extends WebDriverTestCase
{
    /**
     * @var AddPage
     */
    protected $addContentPage;

    /**
     * @var ViewPage
     */
    protected $adminNodeView;

    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericTestDataProvider;

    /**
     * @var AssetsPage
     */
    protected $assetsPage;

    /**
     * @var ContentCreationService
     */
    protected $contentCreationService;

    /**
     * @var DrupalSearchApiApi
     */
    protected $drupalSearchApiApi;

    /**
     * @var EditPage
     */
    protected $editPage;

    /**
     * @var PanelsContentPage
     */
    protected $landingPagePanelsPage;

    /**
     * @var NodeViewPage
     */
    protected $nodeViewPage;

    /**
     * Atom id's of atoms that should be deleted in the tearDown method.
     *
     * @var int[]
     */
    protected $tearDownAtoms;

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

        // Each test should start with an empty array of atoms to tear down at
        // the end of the test.
        $this->tearDownAtoms = array();

        // Create some instances to use later on.
        $this->addContentPage = new AddPage($this);
        $this->adminNodeView = new ViewPage($this);
        $this->alphanumericTestDataProvider = new AlphanumericTestDataProvider();
        $this->assetsPage = new AssetsPage($this);
        $this->drupalSearchApiApi = new DrupalSearchApiApi($this);
        $this->editPage = new EditPage($this);
        $this->landingPagePanelsPage = new PanelsContentPage($this);
        $this->nodeViewPage = new NodeViewPage($this);
        $this->userSessionService = new UserSessionService($this);
        $this->contentCreationService = new ContentCreationService($this, $this->userSessionService);

        // Log in as chief editor.
        $this->userSessionService->login('ChiefEditor');
    }

    /**
     * Tests if the vocabularies of the Scald module are not present.
     *
     * @group scald
     * @group taxonomy
     */
    public function testScaldVocabulariesRemoved()
    {
        $dashboard = new DashboardPage($this);
        $dashboard->checkArrival();
        $dashboard->adminMenuLinks->linkStructure->click();

        $menu_manager = new MenuOverviewPage($this);
        $menu_manager->checkArrival();
        $menu_manager->adminMenuLinks->linkTaxonomy->click();

        $taxonomy_manager = new OverviewPage($this);
        $taxonomy_manager->checkArrival();

        $scald_vocabulary_names = array('Authors', 'Scald tags');

        foreach ($taxonomy_manager->vocabularyLinks as $link) {
            $text = $link->text();
            $this->assertFalse(in_array($text, $scald_vocabulary_names));
        }
    }

    /**
     * Tests the CKEditor integration.
     *
     * @todo Test HTMLpurifier (KANWEBS-1142).
     * @todo Adapt when document atoms are enabled (KANWEBS-1996).
     *
     * @group editing
     * @group scald
     * @group wysiwyg
     * @group placeholderText
     */
    public function testCKEditorIntegration()
    {
        // Remove all the atoms.
        $clean_up_service = new CleanUpService($this);
        $clean_up_service->deleteEntities('scald_atom');

        // Create a basic page.
        $nid = $this->contentCreationService->createBasicPage();

        // Go to its properties page.
        $this->adminNodeView->go($nid);
        $this->adminNodeView->contextualToolbar->buttonPageProperties->click();
        $this->editPage->checkArrival();
        $this->editPage->body->waitUntilReady();

        // Maximize the editor to avoid problems clicking elements.
        $this->editPage->body->maximizeWindow();

        // Open the scald library modal.
        $this->editPage->body->buttonOpenScaldLibraryModal->click();
        $library_modal = new LibraryModal($this);
        $library_modal->waitUntilOpened();

        // Make sure there are no atoms.
        $this->assertTextPresent('No media have been added to the library yet.');

        // Add a new image.
        $library_modal->addAssetButton->click();

        $add_asset_modal = new AddAssetModal($this);
        $add_asset_modal->waitUntilOpened();
        $add_asset_modal->imageLink->click();

        $add_modal = new AddAtomModal($this);
        $add_modal->waitUntilOpened();

        $image_path = $this->sampleImage();
        $image = $this->file($image_path);
        $title = basename($image_path);

        $add_modal->form->fileList->uploadFiles($image);

        // Add an alternative text.
        $options_modal = new AddOptionsModal($this);
        $options_modal->waitUntilOpened();

        $alt_text = $this->alphanumericTestDataProvider->getValidValue(6);

        $options_modal->form->alternativeText->fill($alt_text);
        $options_modal->form->finishButton->click();

        // Make sure the image is added to the library correctly, and get its
        // atom id.
        $library_modal->waitUntilOpened();
        $this->assertTextPresent('Atom ' . $title . ', of type Image has been created.');

        $atom = $library_modal->library->items[0];
        $atom_id = $atom->atomId;

        // Insert the atom in the CKEditor.
        $atom->insertLink->click();
        $library_modal->waitUntilClosed();

        // Double-click the image in the CKEditor.
        $test_case = $this;
        $callable = new SerializableClosure(
            function () use ($test_case, $atom_id) {
                $xpath = '//img[contains(@class, "atom-id-' . $atom_id . '")]';
                $test_case->waitUntilElementIsPresent($xpath);
                $img = $test_case->byXPath($xpath);
                $test_case->moveto($img);
                $test_case->doubleclick();
            }
        );
        $this->editPage->body->inIframe($callable);

        // Wait for the image properties modal to open.
        $image_modal = $this->editPage->body->modalImageProperties;
        $image_modal->waitUntilOpened();

        // Make sure the URL and Alternative Text fields are disabled.
        $this->assertFalse($image_modal->imageInfoForm->url->getWebdriverElement()
            ->enabled());
        $this->assertFalse($image_modal->imageInfoForm->alternativeText->getWebdriverElement()
            ->enabled());

        // Set the width and the height.
        $width = '300';
        $height = '200';
        $image_modal->imageInfoForm->width->fill($width);
        $image_modal->imageInfoForm->height->fill($height);

        // Switch to the Advanced tab. Make sure to wait until it's displayed.
        $image_modal->tabs->linkAdvanced->click();
        $image_modal->waitUntilTabDisplayed(ImagePropertiesModalAdvancedForm::TABNAME);

        // Make sure that the Stylesheet Classes field is disabled.
        $this->assertFalse($image_modal->advancedForm->stylesheetClasses->getWebdriverElement()
            ->enabled());

        // Update the image properties. (Width)
        $image_modal->submit();
        $image_modal->waitUntilClosed();

        // Normalize the editor now.
        $this->editPage->body->normalizeWindow();

        // Save the page, check that the image appears correctly in the admin
        // view.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminNodeView->checkArrival();
        $this->assertImageAtomPresent($atom_id, $alt_text, $width);

        $this->nodeViewPage->go($nid);

        $xpath = '//div[@class="content"]//img[contains(@class, "atom-id-' . $atom_id . '")]';
        $element = $this->element($this->using('xpath')->value($xpath));
        $src = $element->attribute('src');
        // Just check if the dimensions are in the source because the same image
        // is used multiple times.
        $this->assertContains('-300x200', $src);

        // Go to the central assets library, and change the alt text of the
        // image we just inserted into the CKEditor.
        $this->assetsPage->go();
        $atom = $this->assetsPage->library->items[0];
        $atom->editLink->click();

        $new_alt_text = $alt_text . '2';

        $options_modal->waitUntilOpened();
        $options_modal->form->alternativeText->fill($new_alt_text);
        $options_modal->form->finishButton->click();
        $options_modal->waitUntilClosed();

        $this->waitUntilTextIsPresent('Atom ' . $title . ', of type Image has been updated.');

        // Go back to the admin view of the basic page, and make sure the image
        // atom is still present and that its alt text has been updated here as
        // well. Also make sure the width is still set to 300.
        $this->adminNodeView->go($nid);
        $this->assertImageAtomPresent($atom_id, $new_alt_text, $width);

        // Test the document atoms.
        $this->adminNodeView->contextualToolbar->buttonPageProperties->click();
        $this->editPage->checkArrival();
        $this->editPage->body->waitUntilReady();

        // Open the scald library modal.
        $this->editPage->body->buttonOpenScaldLibraryModal->click();
        $library_modal = new LibraryModal($this);
        $library_modal->waitUntilOpened();

        // Add a new asset.
        $library_modal->addAssetButton->click();
        $add_asset_modal = new AddAssetModal($this);
        $add_asset_modal->waitUntilOpened();
        $add_asset_modal->fileLink->click();

        // Upload a new document.
        $add_document_modal = new AddAtomModal($this);
        $add_document_modal->waitUntilOpened();

        $doc_path = $this->sampleDocument();
        $title = basename($doc_path);
        $document = $this->file($doc_path);

        $add_document_modal->form->fileList->uploadFiles($document);

        $options_modal = new AddDocumentOptionsModal($this);
        $options_modal->waitUntilOpened();
        $options_modal->form->finishButton->click();

        // Wait until the library is reloaded.
        $options_modal->waitUntilClosed();
        $this->waitUntilTextIsPresent('Atom ' . $title . ', of type File has been created.');

        $atom = $library_modal->library->items[0];
        $atom_id = $atom->atomId;

        // Insert the atom in the CKEditor.
        $atom->insertLink->click();
        $library_modal->waitUntilClosed();

        // Double-click the document in the CKEditor.
        $callable = new SerializableClosure(
            function () use ($test_case, $atom_id) {
                $xpath = '//span[contains(@class, "atom-id-' . $atom_id . '")]';
                $test_case->waitUntilElementIsPresent($xpath);
                $document = $test_case->byXPath($xpath);
                $test_case->moveto($document);
                $test_case->doubleclick();
                // Make sure the document is disabled for editing anyway.
                $test_case->assertEquals('false', $document->attribute('contenteditable'));
                // @todo find a way to verify that nothing happens when we try to
                // edit the document atom by double clicking.
            }
        );
        $this->editPage->body->inIframe($callable);

        // Save the page, check that the document appears correctly in the
        // admin node view.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminNodeView->checkArrival();
        $filename = explode('.', $title);
        $this->assertDocumentAtomPresent($atom_id, $title, $filename[0]);

        // Go to the central assets library, and change the title of the
        // document we just inserted into the CKEditor.
        $this->assetsPage->go();
        $atom = $this->assetsPage->library->items[0];
        $atom->editLink->click();

        $new_title = $title . '2';

        $options_modal->waitUntilOpened();
        $options_modal->form->title->fill($new_title);
        $options_modal->form->finishButton->click();
        $options_modal->waitUntilClosed();

        $this->waitUntilTextIsPresent('Atom ' . $new_title . ', of type File has been updated.');

        // Go back to the admin view of the basic page, and make sure the
        // document atom is still present and that its title has been updated
        // here as well.
        $this->adminNodeView->go($nid);
        $this->assertDocumentAtomPresent($atom_id, $new_title, $filename[0]);

        // Go to the central assets library, and change the title of the
        // document to 128, verify we just inserted into the CKEditor.
        $this->assetsPage->go();
        $atom = $this->assetsPage->library->items[0];
        $atom->editLink->click();

        $new_title = $this->alphanumericTestDataProvider->getValidValue(128);

        $options_modal->waitUntilOpened();
        $this->assertTextPresent('You can only fill out 128 characters for the title.');
        $options_modal->form->title->fill($new_title);
        $options_modal->form->finishButton->click();
        $options_modal->waitUntilClosed();

        $this->waitUntilTextIsPresent('Atom ' . $new_title . ', of type File has been updated.');

        // Go back to the admin view of the basic page, and make sure the
        // document atom is still present and that its title has been updated
        // here as well.
        $this->adminNodeView->go($nid);
        $this->assertDocumentAtomPresent($atom_id, $new_title, $filename[0]);
    }

    /**
     * Tests the CKEditor integration for video atoms.
     *
     * @group editing
     * @group scald
     * @group wysiwyg
     */
    public function testVideoCKEditorIntegration()
    {
        // Add a page to work with.
        $service = new ContentCreationService($this, $this->userSessionService);
        $nid = $service->createBasicPage();
        $this->adminNodeView->go($nid);

        // Test both types of videos.
        foreach (array('video_file', 'youtube') as $video_type) {
            $this->adminNodeView->contextualToolbar->buttonPageProperties->click();
            $this->editPage->checkArrival();
            $this->editPage->body->waitUntilReady();

            // Open the scald library modal.
            $this->editPage->body->setBodyText('');
            $this->editPage->body->buttonOpenScaldLibraryModal->click();
            $library_modal = new LibraryModal($this);
            $library_modal->waitUntilOpened();

            // Add a new asset.
            $library_modal->addAssetButton->click();
            $add_asset_modal = new AddAssetModal($this);
            $add_asset_modal->waitUntilOpened();
            $add_asset_modal->videoLink->click();

            // Pick the appropriate source.
            $source_modal = new SourceModal($this);
            $source_modal->waitUntilOpened();
            $source_modal->chooseSource("paddle_scald_$video_type");

            $test_data = array();
            if ($video_type == 'video_file') {
                // Upload a new video file.
                $add_modal = new AddAtomModal($this);
                $add_modal->waitUntilOpened();

                $video_path = $this->sampleVideoFile();
                $video_file = $this->file($video_path);

                $add_modal->form->fileList->uploadFiles($video_file);

                $options_modal = new AddVideoFileOptionsModal($this);
                $options_modal->waitUntilOpened();

                $metadata = $this->sampleVideoFileMetaData();

                $thumbnail_path = $this->sampleVideoThumbnail();
                $thumbnail_file = $this->file($thumbnail_path);
                $options_modal->form->thumbnail->chooseFile($thumbnail_file);

                $subtitles_path = $this->sampleSubtitlesFile();
                $subtitles_file = $this->file($subtitles_path);
                $options_modal->form->subtitles->chooseFile($subtitles_file);

                $options_modal->form->width->fill($metadata['width']);
                $options_modal->form->height->fill($metadata['height']);

                $test_data['type'] = 'video/mp4';
                $test_data['video_filename'] = 'sample_video';
                $test_data = array_merge($test_data, $metadata);
                $test_data['poster'] = 'sample_video';
            } else {
                $add_modal = new AddModal($this);
                $add_modal->waitUntilOpened();

                $youtube_url = $this->sampleYoutubeUrl();
                $add_modal->form->url->fill($youtube_url);
                $add_modal->form->continueButton->click();

                // Wait for the options modal to open, and add subtitles.
                $options_modal = new AddVideoYoutubeOptionsModal($this);
                $options_modal->waitUntilOpened();

                $metadata['title'] = 'USUAL SUSPECTS - Fite Dem Back (LKJ)';

                $this->assertEquals($metadata['title'], $options_modal->form->title->getContent());

                $subtitles_path = $this->sampleSubtitlesFile();
                $subtitles_file = $this->file($subtitles_path);
                $options_modal->form->subtitles->chooseFile($subtitles_file);

                $test_data['type'] = 'video/youtube';
                $test_data['video_filename'] = $youtube_url;
                $test_data['poster'] = str_replace('https://www.youtube.com/watch?v=', '', $youtube_url);
            }
            $test_data['subtitles'] = 'sample_subtitles';

            $options_modal->form->finishButton->click();
            // Wait until the library is reloaded.
            $options_modal->waitUntilClosed();
            $library_modal->waitUntilReloaded();
            $this->waitUntilTextIsPresent('Atom ' . $metadata['title'] . ', of type Video has been created.');

            $atom = $library_modal->library->items[0];
            $atom_id = $atom->atomId;
            $test_data['atom_id'] = $atom_id;

            // Make sure the atom gets deleted after the test.
            $this->tearDownAtoms[] = $atom_id;

            // Insert the atom in the CKEditor.
            $atom->insertLink->click();
            $library_modal->waitUntilClosed();

            // Save the page, check that the video appears correctly in the
            // admin node view.
            $this->editPage->contextualToolbar->buttonSave->click();
            $this->adminNodeView->checkArrival();
            $this->adminNodeView->contextualToolbar->buttonPageProperties->click();
            $this->editPage->checkArrival();

            // Check that there are no empty p ("<p>&nbsp;</p>").
            $test_case = $this;
            $callable = new SerializableClosure(
                function () use ($test_case, $atom_id) {
                    $xpath = '//p[text() = "&nbsp;"]';
                    $elements = $test_case->elements($test_case->using('xpath')
                        ->value($xpath));
                    $test_case->assertCount(0, $elements);
                }
            );
            $this->editPage->body->inIframe($callable);
            $this->editPage->contextualToolbar->buttonSave->click();
            $this->adminNodeView->checkArrival();

            $this->assertVideoAtomPresent($test_data);

            // Go to the central assets library and change the title and
            // subtitles.
            $this->assetsPage->go();
            $atom = $this->assetsPage->library->items[0];
            $atom->editLink->click();
            $options_modal->waitUntilOpened();

            // Introduce some changes.
            $test_data['title'] = $metadata['title'] . ' 2';
            $test_data['subtitles'] = '';
            $options_modal->form->subtitles->clear();
            $options_modal->form->title->fill($test_data['title']);
            $options_modal->form->finishButton->click();
            $options_modal->waitUntilClosed();

            $this->waitUntilTextIsPresent('Atom ' . $test_data['title'] . ', of type Video has been updated.');

            $this->adminNodeView->go($nid);
            $this->assertVideoAtomPresent($test_data);
        }
    }

    /**
     * Asserts that a given image atom is present on the page.
     *
     * @param int $atom_id
     *   Atom ID of the image.
     * @param string $alt_text
     *   Alternative text of the image.
     * @param string $width
     *   Width style property of the image. (Without unit)
     */
    public function assertImageAtomPresent(
        $atom_id,
        $alt_text = '',
        $width = ''
    ) {
        // Normally we shouldn't use XPath selectors in our tests, but this is
        // an element inserted into the CKEditor so doesn't make much sense to
        // put this in a separate class.
        $xpath = '//img';
        $xpath .= '[contains(@class, "atom-id-' . $atom_id . '")]';

        if (!empty($alt_text)) {
            $xpath .= '[@alt="' . $alt_text . '"]';
        }

        if (!empty($width)) {
            $xpath .= '[contains(@style, "width:' . $width . 'px")]';
        }

        $criteria = $this->using('xpath')->value($xpath);
        $elements = $this->elements($criteria);

        $this->assertCount(1, $elements);
    }

    /**
     * Asserts that a given document atom is present on the page.
     *
     * @param int $atom_id
     *   Atom ID of the document.
     * @param string $title
     *   The title of the document.
     * @param string $filename
     *   The filename of the document without the file extension.
     */
    public function assertDocumentAtomPresent($atom_id, $title, $filename)
    {
        // Normally we shouldn't use XPath selectors in our tests, but this is
        // an element inserted into the CKEditor so doesn't make much sense to
        // put this in a separate class.
        $xpath = '//span[contains(@class, "atom-id-' . $atom_id . '")]/a[contains(text(), "' . $title . '") and contains(@href, "' . $filename . '") and contains(@target, _blank)]';

        $criteria = $this->using('xpath')->value($xpath);
        $elements = $this->elements($criteria);

        $this->assertCount(1, $elements);
    }

    /**
     * Asserts that a given video atom is present on the page.
     *
     * @param array $test_data
     *   Array with the following values:
     *   - int $atom_id - Atom ID of the video.
     *   - string $poster - The filename of the poster.
     *   - string $video_filename - The filename/location of the video.
     *   - string $type - The type of the video - "mp4" or "youtube".
     *   - string $subtitles - The filename of the subtitles.
     */
    public function assertVideoAtomPresent($test_data)
    {
        // Normally we shouldn't use XPath selectors in our tests, but this is
        // an element inserted into the CKEditor so doesn't make much sense to
        // put this in a separate class.
        $xpath = '//video[contains(@class, "atom-id-' . $test_data['atom_id'] . '")]';

        $criteria = $this->using('xpath')->value($xpath);
        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element[] $videos */
        $videos = $this->elements($criteria);

        $this->assertCount(1, $videos);
        $video = $videos[0];

        // Check the width and height.
        // Check the width and height.
        $style = str_replace(' ', '', $video->attribute('style'));
        $this->assertTrue(strpos($style, 'width:100%;') !== false);
        // Regression, since media-element 3, the height is only 100% for YouTube videos.
        if ($test_data['type'] == 'video/youtube') {
            $this->assertTrue(strpos($style, 'height:100%;') !== false);
        } else {
            $this->assertFalse(strpos($style, 'height:100%;') !== false);
        }

        // Check the poster.
        $this->assertTrue(strpos($video->attribute('poster'), $test_data['poster']) !== false);

        // Check the hard-coded attributes.
        $this->assertEquals('none', $video->attribute('preload'));

        // Check the video was processed by Media element.
        $classes = explode(' ', $video->attribute('class'));
        $this->assertTrue(in_array('mediaelement-processed', $classes));

        // Check the source tag.
        $criteria = $this->using('xpath')->value('./source');
        $sources = $video->elements($criteria);
        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element[] $sources */
        $this->assertCount(1, $sources);
        $this->assertTrue(strpos($sources[0]->attribute('src'), $test_data['video_filename']) !== false);
        $this->assertEquals($test_data['type'], $sources[0]->attribute('type'));

        // Check the track tag.
        $criteria = $this->using('xpath')->value('./track');
        $tracks = $video->elements($criteria);
        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element[] $tracks */
        if (!empty($test_data['subtitles'])) {
            $this->assertCount(1, $tracks);
            $this->assertTrue(strpos($tracks[0]->attribute('src'), $test_data['subtitles']) !== false);
            $this->assertEquals('subtitles', $tracks[0]->attribute('kind'));
        } else {
            $this->assertCount(0, $tracks);
        }
    }

    /**
     * Tests the central assets library page.
     *
     * @group modals
     * @group scald
     */
    public function testCentralAssetsPage()
    {
        // Test the image atoms first.
        // Go to the central assets page.
        $this->assetsPage->go();

        // Add a new asset.
        $this->assetsPage->contextualToolbar->buttonAddNewAsset->click();
        $add_asset_modal = new AddAssetModal($this);
        $add_asset_modal->waitUntilOpened();
        $add_asset_modal->imageLink->click();

        // Upload a new image.
        $add_image_modal = new AddAtomModal($this);
        $add_image_modal->waitUntilOpened();

        $image_path = $this->sampleImage();
        $title = basename($image_path);
        $image = $this->file($image_path);

        $add_image_modal->form->fileList->uploadFiles($image);

        // Make sure we provide an alternative text in the options form.
        $options_modal = new AddOptionsModal($this);
        $options_modal->waitUntilOpened();
        $alt = $this->alphanumericTestDataProvider->getValidValue(6);
        $options_modal->form->alternativeText->fill($alt);
        $options_modal->form->finishButton->click();

        // Wait until the library is reloaded.
        $options_modal->waitUntilClosed();
        $this->waitUntilTextIsPresent('Atom ' . $title . ', of type Image has been created.');

        // Get the newly created atom from the library, and click its edit link.
        $atom = $this->assetsPage->library->items[0];
        $atom->editLink->click();

        // Change the title of the atom.
        $options_modal->waitUntilOpened();
        $new_image_name = $title . '2';
        $options_modal->form->title->fill($new_image_name);
        $options_modal->form->finishButton->click();

        // Wait until the library is reloaded.
        $options_modal->waitUntilClosed();
        $this->waitUntilTextIsPresent('Atom ' . $new_image_name . ', of type Image has been updated.');

        // Delete the atom and make sure it's gone after reloading the library.
        // We first need to get the atom from the library again, as the library
        // was reloaded.
        $atom = $this->assetsPage->library->items[0];
        $atom_id = $atom->atomId;
        $atom->deleteLink->click();

        $delete_modal = new DeleteModal($this);
        $delete_modal->waitUntilOpened();
        $delete_modal->form->deleteButton->click();
        $delete_modal->waitUntilClosed();
        $this->waitUntilTextIsPresent('Image ' . $new_image_name . ' has been deleted.');

        $this->assertFalse(scald_atom_load($atom_id));

        // Test the document atoms next.
        // Go to the central assets page.
        $this->assetsPage->go();

        // Add a new asset.
        $this->assetsPage->contextualToolbar->buttonAddNewAsset->click();
        $add_asset_modal = new AddAssetModal($this);
        $add_asset_modal->waitUntilOpened();
        $add_asset_modal->fileLink->click();

        // Upload a new document.
        $add_document_modal = new AddAtomModal($this);
        $add_document_modal->waitUntilOpened();

        $doc_path = $this->sampleDocument();
        $title = basename($doc_path);
        $document = $this->file($doc_path);

        $add_document_modal->form->fileList->uploadFiles($document);

        $options_modal = new AddDocumentOptionsModal($this);
        $options_modal->waitUntilOpened();
        $options_modal->form->finishButton->click();

        // Wait until the library is reloaded.
        $options_modal->waitUntilClosed();
        $this->waitUntilTextIsPresent('Atom ' . $title . ', of type File has been created.');

        // Get the newly created atom from the library, and click its edit link.
        $atom = $this->assetsPage->library->items[0];
        $atom->editLink->click();

        // Change the title of the atom.
        $options_modal->waitUntilOpened();
        $new_doc_name = $title . '2';
        $options_modal->form->title->fill($new_doc_name);
        $options_modal->form->finishButton->click();

        // Wait until the library is reloaded.
        $options_modal->waitUntilClosed();
        $this->waitUntilTextIsPresent('Atom ' . $new_doc_name . ', of type File has been updated.');

        // @todo Test the search functionality here, in KANWEBS-2078.

        // Delete the atom and make sure it's gone after reloading the library.
        // We first need to get the atom from the library again, as the library
        // was reloaded.
        $atom = $this->assetsPage->library->items[0];
        $atom_id = $atom->atomId;
        $atom->deleteLink->click();

        $delete_modal = new DeleteModal($this);
        $delete_modal->waitUntilOpened();
        $delete_modal->form->deleteButton->click();
        $delete_modal->waitUntilClosed();
        $this->waitUntilTextIsPresent('File ' . $new_doc_name . ' has been deleted.');

        $this->assertFalse(scald_atom_load($atom_id));
    }

    /**
     * Tests to delete files if removal date passed.
     *
     * @group modals
     * @group scald
     */
    public function testCentralFileDelete()
    {
        // Test the document atoms.
        // Go to the central assets page.
        $this->assetsPage->go();

        // Add a new asset.
        $this->assetsPage->contextualToolbar->buttonAddNewAsset->click();
        $add_asset_modal = new AddAssetModal($this);
        $add_asset_modal->waitUntilOpened();
        $add_asset_modal->fileLink->click();

        // Upload a new document.
        $add_document_modal = new AddAtomModal($this);
        $add_document_modal->waitUntilOpened();

        $doc_path = $this->sampleDocument();
        $title = basename($doc_path);
        $document = $this->file($doc_path);
        $add_document_modal->form->fileList->uploadFiles($document);

        $options_modal = new AddDocumentOptionsModal($this);
        $options_modal->waitUntilOpened();
        $options_modal->form->finishButton->click();

        // Wait until the library is reloaded.
        $options_modal->waitUntilClosed();
        $this->waitUntilTextIsPresent('Atom ' . $title . ', of type File has been created.');

        // Get the newly created atom from the library, and click its edit link.
        $atom = $this->assetsPage->library->items[0];
        $atom_id = $atom->atomId;
        $atom->editLink->click();

        // Change the title of the atom.
        $options_modal->waitUntilOpened();
        $options_modal->form->checkboxDeleteDate->check();

        // Give the file a date in the past.
        $today = strtotime('now');
        $end_date = date('d/m/Y', $today);
        $options_modal->form->removeDate->fill($end_date);
        $options_modal->form->removeTime->fill('00:00');
        $options_modal->form->finishButton->click();

        // Wait until the library is reloaded.
        $options_modal->waitUntilClosed();
        $this->waitUntilTextIsPresent('Atom ' . $title . ', of type File has been updated.');

        // Run cron to delete files
        paddle_scald_cron();

        // Index the page and commit the search index.
        $this->drupalSearchApiApi->indexItems('paddle_atoms');
        $this->drupalSearchApiApi->commitIndex('paddle_atoms');

        // Verify that the atom has been deleted.
        $this->assertFalse(scald_atom_load($atom_id));
    }

    /**
     * Tests the edit and delete functionality in the library modal.
     *
     * @group modals
     * @group panes
     * @group scald
     */
    public function testLibraryModalEditDelete()
    {
        // Start from a clean slate.
        $drupalAtomApi = new DrupalAtomApi($this);
        $drupalAtomApi->deleteAllAtoms();

        $this->createLandingPage();

        // Go to the LP's layout page.
        $this->adminNodeView->contextualToolbar->buttonPageLayout->click();
        $this->landingPagePanelsPage->checkArrival();

        // Add a new pane.
        $region = $this->landingPagePanelsPage->display->getRandomRegion();
        $region->buttonAddPane->click();

        // Create a new image pane.
        $image_content_type = new ImagePanelsContentType($this);
        $add_pane_modal = new AddPaneModal($this);
        $add_pane_modal->waitUntilOpened();
        $add_pane_modal->selectContentType($image_content_type);

        // Click the select image button.
        $form_element = $this->byXPath($image_content_type->formElementXPathSelector);
        $image_configuration_form = new ConfigurationForm($this, $form_element);
        $image_configuration_form->image->selectButton->click();

        // Add a new image.
        $library_modal = new LibraryModal($this);
        $library_modal->waitUntilOpened();
        $library_modal->addAssetButton->click();

        $add_modal = new AddAtomModal($this);
        $add_modal->waitUntilOpened();

        $image_path = $this->sampleImage();
        $add_modal->form->fileList->uploadFiles($this->file($image_path));

        // Leave the options as they are for now.
        $options_modal = new AddOptionsModal($this);
        $options_modal->waitUntilOpened();

        // Title is by default the base name of the uploaded image file.
        $title = basename($image_path);
        $this->assertEquals($title, $options_modal->form->title->getContent());

        // Fill in the remaining required fields and finish.
        $options_modal->form->alternativeText->fill('Alt text');
        $options_modal->form->finishButton->click();

        // Wait for the library to open again.
        $library_modal->waitUntilOpened();
        $this->assertTextPresent('Atom ' . $title . ', of type Image has been created.');

        // First item in the library should be the new image.
        $item = $library_modal->library->items[0];

        // Click the edit link, set a new title, and save.
        $item->editLink->click();
        $options_modal->waitUntilOpened();
        $new_image_name = 'New Image Name';
        $options_modal->form->title->fill($new_image_name);
        $options_modal->form->finishButton->click();

        // Wait for the library to open again.
        $library_modal->waitUntilOpened();
        $this->assertTextPresent('Atom ' . $new_image_name . ', of type Image has been updated.');

        // Click the delete button. Click cancel and verify that the image was
        // not deleted.
        $item_count = count($library_modal->library->items);
        $item = $library_modal->library->items[0];
        $item->deleteLink->click();

        $delete_modal = new DeleteModal($this);
        $delete_modal->waitUntilOpened();
        $delete_modal->form->cancelButton->click();

        $library_modal->waitUntilOpened();
        $this->assertCount($item_count, $library_modal->library->items);

        // Click the delete button again, this time confirming the action.
        $item = $library_modal->library->items[0];
        $item->deleteLink->click();

        $delete_modal->waitUntilOpened();
        $delete_modal->form->deleteButton->click();

        // Make sure the image has been deleted.
        $library_modal->waitUntilOpened();
        $this->assertTextPresent('Image ' . $new_image_name . ' has been deleted.');
        $this->assertCount($item_count - 1, $library_modal->library->items);

        // Close the modals and cleanup. We don't need to save the pane.
        $library_modal->close();
        $library_modal->waitUntilClosed();
        $add_pane_modal->close();
        $add_pane_modal->waitUntilClosed();
        $this->landingPagePanelsPage->contextualToolbar->buttonBack->click();
    }

    /**
     * Creates a landing page.
     *
     * @return int
     *   The node ID of the landing page.
     */
    protected function createLandingPage()
    {
        $layout = new Paddle2Col3to9Layout();
        $this->addContentPage->go();
        $nid = $this->addContentPage->createLandingPage($layout->id());
        $this->adminNodeView->checkArrival();

        return $nid;
    }

    /**
     * Tests the required fields in the image add / edit modal dialogs.
     *
     * @group scald
     */
    public function testImageRequiredFields()
    {
        $this->createLandingPage();

        // Go to the LP's layout page.
        $this->adminNodeView->contextualToolbar->buttonPageLayout->click();
        $this->landingPagePanelsPage->checkArrival();

        // Add a new pane.
        $region = $this->landingPagePanelsPage->display->getRandomRegion();
        $region->buttonAddPane->click();

        // Create a new image pane.
        $image_content_type = new ImagePanelsContentType($this);
        $add_pane_modal = new AddPaneModal($this);
        $add_pane_modal->waitUntilOpened();
        $add_pane_modal->selectContentType($image_content_type);

        // Click the select image button.
        $form_element = $this->byXPath($image_content_type->formElementXPathSelector);
        $image_configuration_form = new ConfigurationForm($this, $form_element);
        $image_configuration_form->image->selectButton->click();

        // Add a new image.
        $library_modal = new LibraryModal($this);
        $library_modal->waitUntilOpened();
        $library_modal->addAssetButton->click();

        $add_modal = new AddAtomModal($this);
        $add_modal->waitUntilOpened();

        // Fill in the required image.
        $local_image_path = $this->sampleImage();
        $image_path = $this->file($local_image_path);
        $add_modal->form->fileList->uploadFiles($image_path);

        // Leave required fields empty in options form.
        $options_modal = new AddOptionsModal($this);
        $options_modal->waitUntilOpened();

        $options_modal->form->title->clear();
        $options_modal->form->image->clear();

        // Fill-in the other non-required fields to test them.
        $atom_options = array(
            'title' => $this->alphanumericTestDataProvider->getValidValue(8),
            'description' => $this->alphanumericTestDataProvider->getValidValue(20),
            'alt_text' => $this->alphanumericTestDataProvider->getValidValue(10),
            'metadata' => $this->alphanumericTestDataProvider->getValidValue(20),
        );
        $options_modal->form->description->fill($atom_options['description']);
        // This field is no longer used. But it might be reintroduced.
        // $options_modal->form->caption->fill($atom_options['caption']);

        $options_modal->form->finishButton->click();

        // Title is required, so message is shown.
        $this->waitUntilTextIsPresent('Title field is required.');

        // Image is required, so message is shown.
        $this->assertTextPresent('Image field is required.');

        // Alternative text is required, so message is shown.
        $this->assertTextPresent('Alternative text field is required.');

        // Fill in required fields in options form.
        $options_modal->form->title->fill($atom_options['title']);
        $options_modal->form->alternativeText->fill($atom_options['alt_text']);
        $options_modal->form->image->chooseFile($image_path);
        $options_modal->form->finishButton->click();

        // Verify confirmation message to ensure the image has been added.
        $library_modal->waitUntilOpened();
        $this->assertTextPresent('Atom ' . $atom_options['title'] . ', of type Image has been created.');

        // Also check required fields on edit.
        $item = $library_modal->library->items[0];
        $item->editLink->click();

        $options_modal->waitUntilOpened();

        // Empty the required fields.
        $options_modal->form->title->clear();
        $options_modal->form->alternativeText->clear();
        $options_modal->form->image->clear();

        // Check that the non-required fields were saved correctly and then
        // clear them to verify that they will be empty.
        $this->assertEquals($atom_options['description'], $options_modal->form->description->getContent());
        // This field is no longer used. But it might be reintroduced.
        // $this->assertEquals($atom_options['caption'], $options_modal->form->caption->getContent());
        $options_modal->form->description->clear();
        // This field is no longer used. But it might be reintroduced.
        // $options_modal->form->caption->clear();

        $options_modal->form->finishButton->click();

        // Title is required, so message is shown.
        $this->waitUntilTextIsPresent('Title field is required.');

        // Image is required, so message is shown.
        $this->assertTextPresent('Image field is required.');

        // Alternative text is required, so message is shown.
        $this->assertTextPresent('Alternative text field is required.');

        // Now fill in all required fields and submit.
        $new_title = $this->alphanumericTestDataProvider->getValidValue(8);

        $options_modal->form->title->fill($new_title);
        $options_modal->form->alternativeText->fill($atom_options['alt_text']);
        $options_modal->form->image->chooseFile($image_path);

        $options_modal->form->finishButton->click();

        // Verify that the update has succeeded this time, by checking if
        // the message is there.
        $this->waitUntilTextIsPresent('Atom ' . $new_title . ', of type Image has been updated.');

        // Open again to check the non-required fields.
        $item = $library_modal->library->items[0];
        $item->editLink->click();

        $options_modal->waitUntilOpened();
        $this->assertEquals('', $options_modal->form->description->getContent());
    }

    /**
     * Get the local path to a sample document.
     *
     * @return string
     *   Local path of a sample document.
     */
    protected function sampleDocument()
    {
        return dirname(__FILE__) . '/../../assets/pdf-sample.pdf';
    }

    /**
     * Get the local path to the replacement sample document.
     *
     * @return string
     *   Local path of a sample document.
     */
    protected function sampleReplacementDocument()
    {
        return dirname(__FILE__) . '/../../assets/pdf-sample-replacement.pdf';
    }

    /**
     * Get the local path to a sample image.
     *
     * @return string
     *   Local path of a sample image.
     */
    protected function sampleImage()
    {
        $image_name = 'sample_image.jpg';
        $image_path = dirname(__FILE__) . '/../../assets/' . $image_name;

        return $image_path;
    }

    /**
     * Tests the rendering of the Scald image atom.
     *
     * @group scald
     */
    public function testImageRendering()
    {
        $this->createLandingPage();

        // Go to the LP's layout page.
        $this->adminNodeView->contextualToolbar->buttonPageLayout->click();
        $this->landingPagePanelsPage->checkArrival();

        // Create a new image pane.
        $region = $this->landingPagePanelsPage->display->getRandomRegion();
        $panes_before = $region->getPanes();
        $region->buttonAddPane->click();
        $image_content_type = new ImagePanelsContentType($this);
        $add_pane_modal = new AddPaneModal($this);
        $add_pane_modal->waitUntilOpened();
        $add_pane_modal->selectContentType($image_content_type);

        // Click the "Select image" button.
        $form_element = $this->byXPath($image_content_type->formElementXPathSelector);
        $image_configuration_form = new ConfigurationForm($this, $form_element);
        $alt_text = $this->alphanumericTestDataProvider->getValidValue(10);
        $image_configuration_form->image->chooseImage($alt_text);

        // Save the new pane.
        $add_pane_modal->waitUntilOpened();
        $add_pane_modal->submit();
        $add_pane_modal->waitUntilClosed();

        // Get the current panes to find the new pane.
        $region->refreshPaneList();
        $panes_after = $region->getPanes();
        $pane_new = current(array_diff_key($panes_after, $panes_before));
        $uuid = $pane_new->getUuid();
        $xpath_selector = $pane_new->getXPathSelector();
        $image_pane = new ImagePane($this, $uuid, $xpath_selector);

        $this->assertTrue($image_pane->checkImageDisplayedInPane('sample_image'));
        $this->assertEquals($alt_text, $image_pane->mainImage->attribute('alt'));

        // This prevents a bug with choosing an image (see
        // https://one-agency.atlassian.net/browse/KANWEBS-2094).
        $this->landingPagePanelsPage->contextualToolbar->buttonSave->click();
        $this->adminNodeView->checkArrival();
        $this->adminNodeView->contextualToolbar->buttonPageLayout->click();
        $this->landingPagePanelsPage->checkArrival();

        // Test the image pane top section as well.
        $pane_new->toolbar->buttonEdit->click();
        $edit_modal = new EditPaneModal($this);
        $edit_modal->waitUntilOpened();

        $image_content_type->topSection->enable->check();
        $image_content_type->topSection->contentTypeRadios->image->select();

        $second_image = dirname(__FILE__) . '/../../assets/budapest.jpg';
        $second_alt_text = $this->alphanumericTestDataProvider->getValidValue(10);
        $image_content_type->topSection->selectNewImage($second_image, $second_alt_text);
        $edit_modal->submit();
        $edit_modal->waitUntilClosed();

        // Check that the image is rendered correctly in the top section.
        $pane_new = new ImagePane($this, $uuid, $xpath_selector);
        $this->assertTrue($pane_new->topSection->checkImageDisplayed('budapest'));
        $this->assertEquals($second_alt_text, $pane_new->topSection->getSectionImage()->attribute('alt'));
    }

    /**
     * Tests the crud actions of video file atoms.
     *
     * @group scald
     */
    public function testVideoFileAtoms()
    {
        // Go to the central assets page and add a new video file.
        $this->assetsPage->go();
        $this->assetsPage->contextualToolbar->buttonAddNewAsset->click();

        // Pick the video atom type.
        $add_asset_modal = new AddAssetModal($this);
        $add_asset_modal->waitUntilOpened();
        $add_asset_modal->videoLink->click();

        // Wait for the source modal to open.
        $source_modal = new SourceModal($this);
        $source_modal->waitUntilOpened();

        // Submit the source modal without picking a source, and make sure that
        // we get a validation error.
        $source_modal->submit();
        $this->waitUntilTextIsPresent('Source field is required.');

        // Now pick the upload source.
        $source_modal->chooseSource('paddle_scald_video_file');

        // Wait for the add modal to open (automatically) and upload a video.
        $add_modal = new AddAtomModal($this);
        $add_modal->waitUntilOpened();

        $video_path = $this->sampleVideoFile();
        $video_file = $this->file($video_path);

        $add_modal->form->fileList->uploadFiles($video_file);

        // Wait for the options modal to open, and upload a thumbnail, subtitles
        // and set the width and height of the movie.
        $options_modal = new AddVideoFileOptionsModal($this);
        $options_modal->waitUntilOpened();

        $metadata = $this->sampleVideoFileMetaData();

        $this->assertEquals($metadata['title'], $options_modal->form->title->getContent());

        $thumbnail_path = $this->sampleVideoThumbnail();
        $thumbnail_file = $this->file($thumbnail_path);
        $options_modal->form->thumbnail->chooseFile($thumbnail_file);

        $subtitles_path = $this->sampleSubtitlesFile();
        $subtitles_file = $this->file($subtitles_path);
        $options_modal->form->subtitles->chooseFile($subtitles_file);

        $options_modal->form->width->fill($metadata['width']);
        $options_modal->form->height->fill($metadata['height']);

        // Check that there is a help text is displayed, explaining the video
        // proportions.
        $text = 'Please fill out the desired proportions (for example width: 16 and height: 9). Common proportions are 16:9 and 4:3. This will define its size on the page before playing the video.';
        $this->assertTextPresent($text);

        $options_modal->form->finishButton->click();
        $options_modal->waitUntilClosed();

        // Wait until the atom is visible in the library.
        $this->waitUntilTextIsPresent('Atom ' . $metadata['title'] . ', of type Video has been created.');

        // Get the atom from the library, and click it's edit link.
        $atom = $this->assetsPage->library->items[0];
        $atom->editLink->click();

        // Wait for the options modal to open again, and change the title.
        $options_modal->waitUntilOpened();
        $new_title = $this->alphanumericTestDataProvider->getValidValue(14);
        $options_modal->form->title->fill($new_title);
        $options_modal->form->finishButton->click();
        $options_modal->waitUntilClosed();

        // Wait until the new title is visible.
        $this->waitUntilTextIsPresent($new_title);

        // Get the atom again from the library. (The list has refreshed so we
        // need to get the "new" atom.
        $atom = $this->assetsPage->library->items[0];
        $atom_id = $atom->atomId;

        // Tests the default image for the video atom.
        $yet_new_title = $this->alphanumericTestDataProvider->getValidValue(14);
        $atom->editLink->click();

        // Remove the thumbnail and change the title to know when the atom has
        // been updated.
        $options_modal->waitUntilOpened();
        $options_modal->form->thumbnail->clear();
        $options_modal->form->title->fill($yet_new_title);
        $options_modal->form->finishButton->click();
        $options_modal->waitUntilClosed();
        $this->waitUntilTextIsPresent($yet_new_title);

        // Get the updated library item.
        $atom = $this->assetsPage->library->items[0];
        $this->assertTrue(strpos($atom->image->attribute('src'), 'paddle_scald_video/icons/default-thumbnail.png') !== false);

        // Click it's delete link, wait for the modal to open and confirm.
        $atom->deleteLink->click();
        $delete_modal = new DeleteModal($this);
        $delete_modal->waitUntilOpened();
        $delete_modal->form->deleteButton->click();
        $delete_modal->waitUntilClosed();

        // Wait until the library is reloaded, and verify that the atom is
        // indeed deleted.
        $this->waitUntilTextIsPresent('Video ' . $yet_new_title . ' has been deleted.');
        $this->assertFalse(scald_atom_load($atom_id));
    }

    /**
     * Get the local path to a sample video.
     *
     * @return string
     *   Local path of a sample video.
     */
    protected function sampleVideoFile()
    {
        $video_name = 'sample_video.mp4';

        return dirname(__FILE__) . '/../../assets/' . $video_name;
    }

    /**
     * Get metadata for the sample video file.
     *
     * @return array
     *   Metadata keyed by the field name.
     */
    protected function sampleVideoFileMetaData()
    {
        return array(
            'title' => 'sample_video.mp4',
            'width' => 560,
            'height' => 320,
        );
    }

    /**
     * Get the local path to a sample video thumbnail.
     *
     * @return string
     *   Local path of a sample video thumbnail.
     */
    protected function sampleVideoThumbnail()
    {
        $thumbnail_name = 'sample_video.jpg';

        return dirname(__FILE__) . '/../../assets/' . $thumbnail_name;
    }

    /**
     * Get the local path to a sample subtitles file.
     *
     * @return string
     *   Local path of a sample subtitles file.
     */
    protected function sampleSubtitlesFile()
    {
        $file_name = 'sample_subtitles.srt';

        return dirname(__FILE__) . '/../../assets/' . $file_name;
    }

    /**
     * Tests the crud actions of youtube atoms.
     *
     * @group scald
     */
    public function testYoutubeAtoms()
    {
        // Go to the central assets page and add a new Youtube video.
        $this->assetsPage->go();
        $this->assetsPage->contextualToolbar->buttonAddNewAsset->click();

        // Pick the video atom type.
        $add_asset_modal = new AddAssetModal($this);
        $add_asset_modal->waitUntilOpened();
        $add_asset_modal->videoLink->click();

        // Pick the "youtube video" source.
        $source_modal = new SourceModal($this);
        $source_modal->waitUntilOpened();
        $source_modal->chooseSource('paddle_scald_youtube');

        // Wait for the add modal to open (automatically) and enter a Youtube
        // url.
        $add_modal = new AddModal($this);
        $add_modal->waitUntilOpened();

        $youtube_url = $this->sampleYoutubeUrl();
        $add_modal->form->url->fill($youtube_url);
        $add_modal->form->continueButton->click();

        // Wait for the options modal to open, and add subtitles.
        $options_modal = new AddVideoYoutubeOptionsModal($this);
        $options_modal->waitUntilOpened();

        $this->assertEquals('USUAL SUSPECTS - Fite Dem Back (LKJ)', $options_modal->form->title->getContent());

        $subtitles_path = $this->sampleSubtitlesFile();
        $subtitles_file = $this->file($subtitles_path);
        $options_modal->form->subtitles->chooseFile($subtitles_file);

        $options_modal->form->finishButton->click();
        $options_modal->waitUntilClosed();

        // Wait until the atom is visible in the library.
        $this->waitUntilTextIsPresent('Atom USUAL SUSPECTS - Fite Dem Back (LKJ), of type Video has been created.');

        // Get the atom from the library, and click it's edit link.
        $atom = $this->assetsPage->library->items[0];
        $atom->editLink->click();

        // Wait for the options modal to open again, and change the title.
        $options_modal->waitUntilOpened();
        $new_title = $this->alphanumericTestDataProvider->getValidValue(14);
        $options_modal->form->title->fill($new_title);
        $options_modal->form->finishButton->click();
        $options_modal->waitUntilClosed();

        // Wait until the new title is visible.
        $this->waitUntilTextIsPresent($new_title);

        // Get the atom again from the library. (The list has refreshed so we
        // need to get the "new" atom.
        $atom = $this->assetsPage->library->items[0];
        $atom_id = $atom->atomId;

        // Click it's delete link, wait for the modal to open and confirm.
        $atom->deleteLink->click();
        $delete_modal = new DeleteModal($this);
        $delete_modal->waitUntilOpened();
        $delete_modal->form->deleteButton->click();
        $delete_modal->waitUntilClosed();

        // Wait until the library is reloaded, and verify that the atom is
        // indeed deleted.
        $this->waitUntilTextIsPresent('Video ' . $new_title . ' has been deleted.');
        $this->assertFalse(scald_atom_load($atom_id));
    }

    /**
     * Asserts that files get redirected when replaced within an atom.
     */
    public function testReplaceAndRedirectScaldFile()
    {
        // Create a basic page.
        $nid = $this->contentCreationService->createBasicPage();

        // Create a document.
        $this->assetsPage->go();
        $this->assetsPage->contextualToolbar->buttonAddNewAsset->click();

        $add_asset_modal = new AddAssetModal($this);
        $add_asset_modal->waitUntilOpened();
        $add_asset_modal->fileLink->click();

        // Upload a new document.
        $add_document_modal = new AddAtomModal($this);
        $add_document_modal->waitUntilOpened();

        $doc_path = $this->sampleDocument();
        $title = basename($doc_path);
        $document = $this->file($doc_path);

        $add_document_modal->form->fileList->uploadFiles($document);

        $options_modal = new AddDocumentOptionsModal($this);
        $options_modal->waitUntilOpened();
        $options_modal->form->finishButton->click();

        // Wait until the library is reloaded.
        $options_modal->waitUntilClosed();

        // Add the document to the basic page.
        $this->editPage->go($nid);
        $this->editPage->body->waitUntilReady();

        // Open the scald library modal.
        $this->editPage->body->buttonOpenScaldLibraryModal->click();
        $library_modal = new LibraryModal($this);
        $library_modal->waitUntilOpened();
        $library_item = $library_modal->library->items[0];

        // Retrieve the atom ID so it shall be removed at the tearDown method.
        $this->tearDownAtoms[] = $library_item->atomId;

        // Insert the atom in the CKEditor.
        $library_item->insertLink->click();
        $library_modal->waitUntilClosed();

        // Save the page.
        $this->editPage->contextualToolbar->buttonSave->click();
        $this->adminNodeView->checkArrival();

        // Assert that the document is shown on the front-end and retrieve the link.
        $this->nodeViewPage->go($nid);
        $document_link_element = $this->byPartialLinkText($title);
        $document_link = $document_link_element->attribute('href');

        // Replace the document.
        $doc_path = $this->sampleReplacementDocument();
        $replacement_title = basename($doc_path);
        $replacement_document = $this->file($doc_path);

        $this->assetsPage->go();
        $assets_library_item = $this->assetsPage->library->items[0];
        $assets_library_item->editLink->click();

        $options_modal->waitUntilOpened();
        $options_modal->form->document->removeButton->click();
        $options_modal->form->document->waitUntilFileRemoved();
        $options_modal->form->document->chooseFile($replacement_document);
        $options_modal->form->document->uploadButton->click();
        $options_modal->form->document->waitUntilFileUploaded();
        $options_modal->form->finishButton->click();
        $options_modal->waitUntilClosed();

        // Assert that new document is shown on the front-end with the same title as the first document.
        $this->nodeViewPage->go($nid);
        $this->byPartialLinkText($title);

        // Retrieve the link.
        $replacement_document_link_element = $this->byPartialLinkText($title);
        $replacement_document_link = $replacement_document_link_element->attribute('href');

        // Assert that the links are not equal.
        $this->assertNotEquals($document_link, $replacement_document_link);

        // Assert that the new document title is not found.
        try {
            $this->byPartialLinkText($replacement_title);
            $this->fail(t('You should find the document title in here.'));
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // land here as expected.
        }

        // Assert that the first link redirects to the second document link.
        $webdriver = $this;
        $callable = function () use ($webdriver, $replacement_document_link) {
            $this->assertEquals($replacement_document_link, $this->url());
        };
        $this->openInNewWindow(
            $document_link,
            $callable
        );
    }

    /**
     * Get the url to a sample youtube video.
     *
     * @return string
     *   URL of a sample youtube video.
     */
    protected function sampleYoutubeUrl()
    {
        return 'https://www.youtube.com/watch?v=aTMbHEoAktM';
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        // Clean up all atoms created in the test. We didn't use the
        // AssetCreationService class to create any atoms, so we have to pass
        // a specific list of atoms to delete.
        if (!empty($this->tearDownAtoms)) {
            AssetCreationService::cleanUp($this, $this->tearDownAtoms);
        }

        parent::tearDown();
    }
}
