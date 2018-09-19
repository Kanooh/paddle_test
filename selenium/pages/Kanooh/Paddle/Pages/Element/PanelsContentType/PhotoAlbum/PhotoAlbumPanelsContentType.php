<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\PhotoAlbum\PhotoAlbumPanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\PhotoAlbum;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\PanelsContentType\SectionedPanelsContentType;

/**
 * The 'Photo album' Panels content type.
 */
class PhotoAlbumPanelsContentType extends SectionedPanelsContentType
{

    /**
     * {@inheritdoc}
     */
    const TYPE = 'photo_album';

    /**
     * {@inheritdoc}
     */
    const TITLE = 'Add a photo album';

    /**
     * {@inheritdoc}
     */
    const DESCRIPTION = 'Add a photo album.';

    /**
     * The general vocabulary tags to filter on.
     *
     * @var string
     */
    public $general_vocabulary_tags;

    /**
     * The tags tags to filter on.
     *
     * @var string
     */
    public $tags_tags;

    /**
     * {@inheritdoc}
     *
     * @todo Refactor to use the Form class.
     */
    public function fillInConfigurationForm(Element $element = null)
    {
        // We use the default settings at this moment.
        $this->disableSections();
    }

    /**
     * {@inheritdoc}
     */
    public function getForm(Element $element = null)
    {
        if (!isset($this->form)) {
            $this->form = new ConfigurationForm($this->webdriver, $this->webdriver->byId('paddle-photo-album-photo-album-content-type-edit-form'));
        }

        return $this->form;
    }
}
