<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder\DownloadFormPage.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder;

use Kanooh\Paddle\Pages\PaddlePage;

/**
 * The download form page of the Paddle Formbuilder module.
 *
 * @property DownloadFormPageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 */
class DownloadFormPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/content_manager/node/%/download';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new DownloadFormPageContextualToolbar($this->webdriver);
        }
        return parent::__get($property);
    }
}
