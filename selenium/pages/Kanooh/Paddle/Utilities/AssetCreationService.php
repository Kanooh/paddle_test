<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Utilities\AssetCreationService.
 */

namespace Kanooh\Paddle\Utilities;

use Drupal\Component\Utility\Random;
use Kanooh\Paddle\Pages\Admin\ContentManager\AssetsPage\AssetsPage;
use Kanooh\Paddle\Pages\Element\Scald\AddAssetModal;
use Kanooh\Paddle\Pages\Element\Scald\AddAtomModal;
use Kanooh\Paddle\Pages\Element\Scald\Document\AddOptionsModal as AddFileOptionsModal;
use Kanooh\Paddle\Pages\Element\Scald\Image\AddOptionsModal as AddImageOptionsModal;
use Kanooh\Paddle\Pages\Element\Scald\LibraryItem;
use Kanooh\Paddle\Pages\Element\Scald\MovieFile\AddOptionsModal as AddVideoOptionsModal;
use Kanooh\Paddle\Pages\Element\Scald\MovieYoutube\AddModal;
use Kanooh\Paddle\Pages\Element\Scald\MovieYoutube\AddOptionsModal as AddYoutubeOptionsModal;
use Kanooh\Paddle\Pages\Element\Scald\SourceModal;
use Kanooh\Paddle\Utilities\DrupalService;
use Kanooh\WebDriver\WebDriverTestCase;

class AssetCreationService
{
    /**
     * The assets overview page.
     *
     * @var AssetsPage
     */
    protected $assetsPage;

    /**
     * The path to the assets folder.
     *
     * @var string
     */
    public $assetsPath;

    /**
     * All atom ids created using this class.
     *
     * This property is static because it's used in the cleanUp() method, which
     * is often called in the tearDown() method of a test. In the tearDown we
     * often need to create a new class object because the old one is no longer
     * available. In that case all created ids would be lost if they're not
     * stored as a static property.
     *
     * @var array
     */
    public static $createdIds;

    /**
     * Random data generator.
     *
     * @var Random
     */
    protected $random;

    /**
     * The Selenium webdriver.
     *
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * Constructs a AssetCreationService object.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param string $assets_path
     *   Path to the assets folder.
     */
    public function __construct(WebDriverTestCase $webdriver, $assets_path = '')
    {
        $this->webdriver = $webdriver;
        $this->assetsPage = new AssetsPage($this->webdriver);
        $this->assetsPath = !empty($assets_path) ? $assets_path : dirname(__FILE__) . '/../../../../tests/Kanooh/Paddle/assets';
        $this->random = new Random();
    }

    /**
     * Creates a new atom, but stops after the type has been chosen.
     *
     * The other steps have to be completed by other functions.
     *
     * @param string $type
     *   Type of the atom to create.
     */
    protected function createAtomOfType($type)
    {
        $this->assetsPage->go();
        $this->assetsPage->contextualToolbar->buttonAddNewAsset->click();

        $add_modal = new AddAssetModal($this->webdriver);
        $add_modal->waitUntilOpened();
        $add_modal->{$type . 'Link'}->click();
    }

    /**
     * Waits until an atom is created by checking for a text message.
     *
     * @param string $title
     *   Title of the new atom.
     * @param string $type
     *   Human-readable type of the atom.
     */
    public function waitUntilAtomCreated($title, $type)
    {
        $message = 'Atom ' . $title . ', of type ' . $type . ' has been created.';
        $this->webdriver->waitUntilTextIsPresent($message);
    }

    /**
     * Returns the id of a newly created atom, and stores it for later cleanup.
     *
     * @return int
     *   Atom id.
     */
    public function getCreatedAtomId()
    {
        // Get the id of the newly created atom.
        $atoms = $this->assetsPage->library->items;
        $id = $atoms[0]->atomId;

        // Store it so we can remove it again later if needed.
        if (!in_array($id, $this->getCreatedIds())) {
            self::$createdIds[] = $id;
        }

        return $id;
    }

    /**
     * Creates an image atom.
     *
     * @param array $data
     *   Associative array with the following optional data:
     *   - path: Path to the image file. Defaults to a sample image.
     *   - title: Title for the image, or false to keep the file name as title.
     *     Defaults to a random string.
     *   - alternative_text: Alternative text. Defaults to a random string.
     *   - caption: Caption for the image. Defaults to a random string.
     *   - description: Description for the image. Defaults to a random string.
     *   - metadata: Metadata for the image. Defaults to a random string.
     *   - image_style: The image style to set for the image.
     *   - tags: an array of tag names to be set.
     *   - general_terms: an array of term ids to be selected for the image.
     *
     * @return array
     *   Data array as documented above, plus an "id" key with the new atom id.
     */
    public function createImage($data = array())
    {
        // To leave any of the fields empty, simply provide an empty string.
        $data += array(
            'path' => $this->assetsPath . '/sample_image.jpg',
            'title' => $this->random->name(12),
            'alternative_text' => $this->random->name(12),
            // This field is no longer used. But it might be reintroduced.
            // 'caption' => $this->random->name(12),
            'description' => $this->random->name(24),
            'metadata' => $this->random->name(24),
            'image_style' => '',
            'tags' => array(),
            'general_terms' => array(),
        );
        $data['title'] = $data['title'] !== false ? $data['title'] : basename($data['path']);
        $image_file = $this->webdriver->file($data['path']);

        $this->createAtomOfType('image');

        $add_modal = new AddAtomModal($this->webdriver);
        $add_modal->waitUntilOpened();
        $add_modal->form->fileList->uploadFiles($image_file);

        $options_modal = new AddImageOptionsModal($this->webdriver);
        $options_modal->waitUntilOpened();

        $options_modal->form->title->fill($data['title']);
        $options_modal->form->alternativeText->fill($data['alternative_text']);
        // This field is no longer used. But it might be reintroduced.
        // $options_modal->form->caption->fill($data['caption']);
        $options_modal->form->description->fill($data['description']);

        // Set the image style if any was passed.
        if ($data['image_style']) {
            $options_modal->form->setCroppedStyle($data['image_style']);
        }

        // Add all the tags to the atom.
        foreach ($data['tags'] as $tag) {
            $options_modal->form->tags->value($tag);
            $options_modal->form->tagsAddButton->click();
            $options_modal->form->waitUntilTagIsDisplayed($tag);
        }

        // Select all the general vocabulary terms.
        foreach ($data['general_terms'] as $tid) {
            $options_modal->form->generalVocabularyTermReferenceTree->selectTerm($tid);
        }

        $options_modal->form->finishButton->click();
        $options_modal->waitUntilClosed();

        $this->waitUntilAtomCreated($data['title'], 'Image');
        $data['id'] = $this->getCreatedAtomId();
        return $data;
    }

    /**
     * Creates a file atom.
     *
     * @param array $data
     *   Associative array with the following optional data:
     *   - path: Path to the file. Defaults to a sample pdf file.
     *   - title: Title for the image, or false to keep the file name as title.
     *     Defaults to a random string.
     *   - tags: an array of tag names to be set.
     *   - general_terms: an array of term ids to be selected for the image.
     *
     * @return array
     *   Data array as documented above, plus an "id" key with the new atom id.
     */
    public function createFile($data = array())
    {
        $data += array(
            'path' => $this->assetsPath . '/pdf-sample.pdf',
            'title' => $this->random->name(12),
            'tags' => array(),
            'general_terms' => array(),
        );
        $data['title'] = $data['title'] !== false ? $data['title'] : basename($data['path']);
        $file = $this->webdriver->file($data['path']);

        $this->createAtomOfType('file');

        $add_modal = new AddAtomModal($this->webdriver);
        $add_modal->waitUntilOpened();
        $add_modal->form->fileList->uploadFiles($file);

        $options_modal = new AddFileOptionsModal($this->webdriver);
        $options_modal->waitUntilOpened();
        $options_modal->form->title->fill($data['title']);

        // Add all the tags to the atom.
        foreach ($data['tags'] as $tag) {
            $options_modal->form->tags->value($tag);
            $options_modal->form->tagsAddButton->click();
            $options_modal->form->waitUntilTagIsDisplayed($tag);
        }

        // Select all the general vocabulary terms.
        foreach ($data['general_terms'] as $tid) {
            $options_modal->form->generalVocabularyTermReferenceTree->selectTerm($tid);
        }

        $options_modal->form->finishButton->click();
        $options_modal->waitUntilClosed();

        $this->waitUntilAtomCreated($data['title'], 'File');
        $data['id'] = $this->getCreatedAtomId();
        return $data;
    }

    /**
     * Creates a video (file) atom.
     *
     * @param array $data
     *   Associative array with the following optional data:
     *   - path: Path to the video file. Defaults to a sample video file.
     *   - title: Title for the video, or false to keep the file name as title.
     *     Defaults to a random string.
     *   - width: Width of the video. Defaults to the sample video width.
     *   - height: Height of the video. Defaults to the sample video height.
     *   - subtitles: Path to the subtitles file. Defaults to a sample file.
     *   - thumbnail: Path to the thumbnail file. Defaults to a sample file.
     *   - tags: an array of tag names to be set.
     *   - general_terms: an array of term ids to be selected for the image.
     *
     * @return array
     *   Data array as documented above, plus an "id" key with the new atom id.
     */
    public function createVideo($data = array())
    {
        // To leave any of the fields empty, simply provide an empty string.
        $data += array(
            'path' => $this->assetsPath . '/sample_video.mp4',
            'title' => $this->random->name(12),
            'width' => 560,
            'height' => 320,
            'subtitles' => $this->assetsPath . '/sample_subtitles.srt',
            'thumbnail' => $this->assetsPath . '/sample_video.jpg',
            'tags' => array(),
            'general_terms' => array(),
        );
        $data['title'] = $data['title'] !== false ? $data['title'] : basename($data['path']);
        $video_file = $this->webdriver->file($data['path']);

        $this->createAtomOfType('video');

        $source_modal = new SourceModal($this->webdriver);
        $source_modal->waitUntilOpened();
        $source_modal->chooseSource('paddle_scald_video_file');

        $add_modal = new AddAtomModal($this->webdriver);
        $add_modal->waitUntilOpened();
        $add_modal->form->fileList->uploadFiles($video_file);

        $options_modal = new AddVideoOptionsModal($this->webdriver);
        $options_modal->waitUntilOpened();

        $options_modal->form->title->fill($data['title']);
        $options_modal->form->width->fill($data['width']);
        $options_modal->form->height->fill($data['height']);

        if (!empty($data['subtitles'])) {
            $subtitles_file = $this->webdriver->file($data['subtitles']);
            $options_modal->form->subtitles->chooseFile($subtitles_file);
        }
        if (!empty($data['thumbnail'])) {
            $thumbnail_file = $this->webdriver->file($data['thumbnail']);
            $options_modal->form->thumbnail->chooseFile($thumbnail_file);
        }

        // Add all the tags to the atom.
        foreach ($data['tags'] as $tag) {
            $options_modal->form->tags->value($tag);
            $options_modal->form->tagsAddButton->click();
            $options_modal->form->waitUntilTagIsDisplayed($tag);
        }

        // Select all the general vocabulary terms.
        foreach ($data['general_terms'] as $tid) {
            $options_modal->form->generalVocabularyTermReferenceTree->selectTerm($tid);
        }

        $options_modal->form->finishButton->click();
        $options_modal->waitUntilClosed();

        $this->waitUntilAtomCreated($data['title'], 'Video');
        $data['id'] = $this->getCreatedAtomId();
        return $data;
    }

    /**
     * Creates a youtube video atom.
     *
     * @param array $data
     *   Associative array with the following optional data:
     *   - identifier: Youtube identifier, can be a url or video id.
     *   - title: Title for the video, or false to keep the youtube title.
     *     Defaults to a random string.
     *   - subtitles: Path to the subtitles file. Defaults to a sample file.
     *   - thumbnail: Path to the thumbnail file, or false to keep the youtube
     *     thumbnail (if any was found). Defaults to a sample file because
     *     sometimes no youtube thumbnail is found.
     *   - tags: an array of tag names to be set.
     *   - general_terms: an array of term ids to be selected for the image.
     *
     * @return array
     *   Data array as documented above, plus an "id" key with the new atom id.
     */
    public function createYoutubeVideo($data = array())
    {
        // To leave any of the fields empty, simply provide an empty string.
        $data += array(
            'identifier' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'title' => $this->random->name(12),
            'subtitles' => $this->assetsPath . '/sample_subtitles.srt',
            'thumbnail' => $this->assetsPath . '/sample_youtube_thumbnail.png',
            'tags' => array(),
            'general_terms' => array(),
        );
        $data['title'] = $data['title'] !== false ? $data['title'] : basename($data['path']);

        $this->createAtomOfType('video');

        $source_modal = new SourceModal($this->webdriver);
        $source_modal->waitUntilOpened();
        $source_modal->chooseSource('paddle_scald_youtube');

        $add_modal = new AddModal($this->webdriver);
        $add_modal->waitUntilOpened();
        $add_modal->form->url->fill($data['identifier']);
        $add_modal->form->continueButton->click();

        $options_modal = new AddYoutubeOptionsModal($this->webdriver);
        $options_modal->waitUntilOpened();

        $options_modal->form->title->fill($data['title']);

        if (!empty($data['subtitles'])) {
            $subtitles_file = $this->webdriver->file($data['subtitles']);
            $options_modal->form->subtitles->chooseFile($subtitles_file);
        }
        if (!empty($data['thumbnail'])) {
            $thumbnail_file = $this->webdriver->file($data['thumbnail']);
            $options_modal->form->thumbnail->chooseFile($thumbnail_file);
        }

        // Add all the tags to the atom.
        foreach ($data['tags'] as $tag) {
            $options_modal->form->tags->value($tag);
            $options_modal->form->tagsAddButton->click();
            $options_modal->form->waitUntilTagIsDisplayed($tag);
        }

        // Select all the general vocabulary terms.
        foreach ($data['general_terms'] as $tid) {
            $options_modal->form->generalVocabularyTermReferenceTree->selectTerm($tid);
        }

        $options_modal->form->finishButton->click();
        $options_modal->waitUntilClosed();

        $this->waitUntilAtomCreated($data['title'], 'Video');
        $data['id'] = $this->getCreatedAtomId();
        return $data;
    }

    /**
     * Deletes all the atoms created via this class, or a specified list of ids.
     *
     * When a list of ids is specified, the atoms created by this class will not
     * be deleted unless the method is called again without a list of ids.
     *
     * This method is static because it is often called in the tearDown() method
     * of a test, where we would otherwise need to create a new object of this
     * class because the old one is often no longer available.
     *
     * @param WebDriverTestCase $webdriver
     *   Webdriver test case to use to interact with the browser.
     * @param array|bool $ids
     *   Optional list of atoms to delete instead of the ones created by this
     *   class.
     */
    public static function cleanUp(WebDriverTestCase $webdriver, $ids = false)
    {
        // If no list of ids is specified, use the list of atoms that were
        // created by this class and reset the list so we don't try to delete
        // them twice if this method is called again later.
        if ($ids === false) {
            $ids = self::getCreatedIds();
            self::resetCreatedIds();
        }

        if (count($ids)) {
            $drupalService = new DrupalService();
            $drupalService->bootstrap($webdriver);
            // Delete all given atom ids without going through the UI.
            entity_delete_multiple('scald_atom', $ids);
        }
    }

    /**
     * Returns a list of atom ids created by this class.
     *
     * @return array
     */
    public static function getCreatedIds()
    {
        return (is_array(self::$createdIds)) ? self::$createdIds : array();
    }

    /**
     * Resets the list of atom ids created by this class.
     */
    protected static function resetCreatedIds()
    {
        self::$createdIds = array();
    }
}
