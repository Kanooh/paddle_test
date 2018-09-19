<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Utilities\ScaldService.
 */

namespace Kanooh\Paddle\Utilities;

use Kanooh\Paddle\Pages\Element\Scald\AddAtomModal;
use Kanooh\Paddle\Pages\Element\Scald\Image\AddOptionsModal as AddImageOptionsModal;
use Kanooh\Paddle\Pages\Element\Scald\MovieFile\AddOptionsModal as AddVideoFileOptionsModal;
use Kanooh\Paddle\Pages\Element\Scald\MovieYoutube\AddModal;
use Kanooh\Paddle\Pages\Element\Scald\MovieYoutube\AddOptionsModal as AddVideoYoutubeOptionsModal;
use Kanooh\Paddle\Pages\Element\Scald\LibraryModal;
use Kanooh\Paddle\Pages\Element\Scald\SourceModal;
use Kanooh\WebDriver\WebDriverTestCase;

class ScaldService
{
    /**
     * The Selenium webdriver.
     *
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * Constructs a new ScaldService.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     */
    public function __construct($webdriver)
    {
        $this->webdriver = $webdriver;
    }

    /**
     * Adds an image to the library. (Intended for the library modal.)
     *
     * This assumes that the library modal has been opened already by the
     * calling method. It will wait for the modal to open before continuing.
     *
     * @deprecated use AssetCreationService unless the modal functionality
     *   needs to be tested.
     *
     * @param string $image
     *   Path to the image to upload.
     * @param string $alt_text
     *   The alt text to put if the image is not displayed.
     *
     * @return int
     *   Atom ID of the uploaded image.
     */
    public function addImageToLibraryModal($image, $alt_text = 'Alternative text')
    {
        $libraryModal = new LibraryModal($this->webdriver);
        $libraryModal->waitUntilOpened();

        // When image is the only allowed type, the "add asset" button functions
        // as the "add new image" button.
        $libraryModal->addAssetButton->click();

        // Do NOT call waitUntilClosed() on $libraryModal here. Actually
        // the modal HTML element is not removed, but instead reused by the
        // AddAtomModal.
        // Wait for the add modal to appear.
        $add_modal = new AddAtomModal($this->webdriver);
        $add_modal->waitUntilOpened();

        $file = $this->webdriver->file($image);
        $add_modal->form->fileList->uploadFiles($file);

        // Do NOT call waitUntilClosed() on $addModal here. Actually
        // the modal HTML element is not removed, but instead reused by the
        // AddImageOptionsModal.
        $addOptionsModal = new AddImageOptionsModal($this->webdriver);
        $addOptionsModal->waitUntilOpened();

        // Fill in required fields.
        $addOptionsModal->form->alternativeText->fill($alt_text);

        // Finish.
        $addOptionsModal->form->finishButton->click();

        // Wait for the options modal to close and the library modal to
        // appear again. Here the modal is really closed first and another
        // one opened, in contrast to the previous 2 modal transitions.
        $addOptionsModal->waitUntilClosed();

        // Use another instance of LibraryModal, because it has its previous
        // unique modal IDs captured. If we do not use a new instance,
        // waitUntilClosed() will not wait
        $libraryModal = new LibraryModal($this->webdriver);
        $libraryModal->waitUntilOpened();

        // Get the first item from the library. It should be our new image.
        $items = $libraryModal->library->items;
        $first_item = reset($items);
        $atom_id = $first_item->atomId;

        return $atom_id;
    }

    /**
     * Adds a video file to the library. (Intended for the library modal.)
     *
     * This assumes that the library modal has been opened already by the
     * calling method. It will wait for the modal to open before continuing.
     *
     * @deprecated use AssetCreationService unless the modal functionality
     *   needs to be tested.
     *
     * @param string $video
     *   Path to the video to upload.
     * @param int $width
     *   The width of the video. Required.
     * @param int $height
     *   The height of the video. Required.
     * @param string $thumbnail_path
     *   Path to the thumbnail image for the video.
     * @param string $subtitles_path
     *   Path to the subtitles for the video.
     *
     * @return int
     *   Atom ID of the new video.
     */
    public function addVideoFileToLibraryModal($video, $width, $height, $thumbnail_path = '', $subtitles_path = '')
    {
        $library_modal = new LibraryModal($this->webdriver);
        $library_modal->waitUntilOpened();

        // When video is the only allowed type, the "add asset" button functions
        // as the "add new video" button.
        $library_modal->addAssetButton->click();

        // Pick the appropriate source.
        $source_modal = new SourceModal($this->webdriver);
        $source_modal->waitUntilOpened();
        $source_modal->chooseSource('paddle_scald_video_file');

        // Do NOT call waitUntilClosed() on $library_modal here. Actually
        // the modal HTML element is not removed, but instead reused by the
        // AddAtomModal.
        // Wait for the add modal to appear.
        $add_modal = new AddAtomModal($this->webdriver);
        $add_modal->waitUntilOpened();

        $file = $this->webdriver->file($video);
        $add_modal->form->fileList->uploadFiles($file);

        // Do NOT call waitUntilClosed() on $add_modal here. Actually
        // the modal HTML element is not removed, but instead reused by the
        // AddVideoOptionsModal.
        $options_modal = new AddVideoFileOptionsModal($this->webdriver);
        $options_modal->waitUntilOpened();

        if (!empty($thumbnail_path)) {
            $thumbnail_file = $this->webdriver->file($thumbnail_path);
            $options_modal->form->thumbnail->chooseFile($thumbnail_file);
        }

        if (!empty($subtitles_path)) {
            $subtitles_file = $this->webdriver->file($subtitles_path);
            $options_modal->form->subtitles->chooseFile($subtitles_file);
        }

        $options_modal->form->width->fill($width);
        $options_modal->form->height->fill($height);

        // Finish.
        $options_modal->form->finishButton->click();

        // Wait for the options modal to close and the library modal to
        // appear again. Here the modal is really closed first and another
        // one opened, in contrast to the previous 2 modal transitions.
        $options_modal->waitUntilClosed();

        // Use another instance of LibraryModal, because it has its previous
        // unique modal IDs captured. If we do not use a new instance,
        // waitUntilClosed() will not wait
        $library_modal = new LibraryModal($this->webdriver);
        $library_modal->waitUntilOpened();

        // Get the first item from the library. It should be our new video.
        $items = $library_modal->library->items;
        $first_item = reset($items);
        $atom_id = $first_item->atomId;

        return $atom_id;
    }

    /**
     * Adds a YouTube video to the library. (Intended for the library modal.)
     *
     * This assumes that the library modal has been opened already by the
     * calling method. It will wait for the modal to open before continuing.
     *
     * @deprecated use AssetCreationService unless the modal functionality
     *   needs to be tested.
     *
     * @param string $video
     *   URL of the video to add.
     * @param string $subtitles_path
     *   Path to the subtitles for the video.
     *
     * @return int
     *   Atom ID of the new video.
     */
    public function addYouTubeVideoToLibraryModal($video, $subtitles_path = '')
    {
        $library_modal = new LibraryModal($this->webdriver);
        $library_modal->waitUntilOpened();

        // When video is the only allowed type, the "add asset" button functions
        // as the "add new video" button.
        $library_modal->addAssetButton->click();

        // Pick the appropriate source.
        $source_modal = new SourceModal($this->webdriver);
        $source_modal->waitUntilOpened();
        $source_modal->chooseSource('paddle_scald_youtube');

        // Do NOT call waitUntilClosed() on $library_modal here. Actually
        // the modal HTML element is not removed, but instead reused by the
        // AddModal.
        // Wait for the add modal to appear.
        $add_modal = new AddModal($this->webdriver);
        $add_modal->waitUntilOpened();

        $add_modal->form->url->fill($video);
        $add_modal->form->continueButton->click();

        // Do NOT call waitUntilClosed() on $add_modal here. Actually
        // the modal HTML element is not removed, but instead reused by the
        // AddVideoOptionsModal.
        $options_modal = new AddVideoYoutubeOptionsModal($this->webdriver);
        $options_modal->waitUntilOpened();

        if (!empty($subtitles_path)) {
            $subtitles_file = $this->webdriver->file($subtitles_path);
            $options_modal->form->subtitles->chooseFile($subtitles_file);
        }

        // Finish.
        $options_modal->form->finishButton->click();

        // Wait for the options modal to close and the library modal to
        // appear again. Here the modal is really closed first and another
        // one opened, in contrast to the previous 2 modal transitions.
        $options_modal->waitUntilClosed();

        // Use another instance of LibraryModal, because it has its previous
        // unique modal IDs captured. If we do not use a new instance,
        // waitUntilClosed() will not wait
        $library_modal = new LibraryModal($this->webdriver);
        $library_modal->waitUntilOpened();

        // Get the first item from the library. It should be our new video.
        $items = $library_modal->library->items;
        $first_item = reset($items);
        $atom_id = $first_item->atomId;

        return $atom_id;
    }

    /**
     * Inserts a specific atom from the library modal.
     *
     * @param int $atom_id
     *   Atom ID of the atom to insert.
     */
    public function insertAtom($atom_id)
    {
        $library_modal = new LibraryModal($this->webdriver);
        $library_modal->waitUntilOpened();

        $atom = $library_modal->library->getAtomById($atom_id);
        $atom->insertLink->click();

        $library_modal->waitUntilClosed();
    }
}
