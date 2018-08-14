<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\AssetsPage\AssetsPage.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\AssetsPage;

use Kanooh\Paddle\Pages\Element\Scald\Library;
use Kanooh\Paddle\Pages\PaddlePage;

/**
 * Central assets library page.
 *
 * @property AssetsPageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property Library $library
 *   The actual library.
 */
class AssetsPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/content_manager/assets';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new AssetsPageContextualToolbar($this->webdriver);
            case 'library':
                return new Library($this->webdriver, '//div[contains(@class, "view-media-library")]', false);
        }
        return parent::__get($property);
    }

    /**
     * {@inheritdoc}
     */
    public function waitUntilPageIsLoaded()
    {
        $this->webdriver->waitUntilElementIsDisplayed(
            '//body[contains(@class, "page-admin-content-manager-assets")]'
        );
    }
}
