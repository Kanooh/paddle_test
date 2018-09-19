<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\AssetsPage\AssetsReferencesPage.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\AssetsPage;

use Kanooh\Paddle\Pages\PaddlePage;

/**
 * Assets references page.
 */
class AssetsReferencesPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/paddle_scald/%/references';

    /**
     * {@inheritdoc}
     */
    public function waitUntilPageIsLoaded()
    {
        $this->webdriver->waitUntilElementIsDisplayed(
            '//body[contains(@class, "page-admin-paddle-scald-references")]'
        );
    }
}
