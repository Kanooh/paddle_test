<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\AssetsPage\AssetsReferencesPage.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Node\ReferencePage;

use Kanooh\Paddle\Pages\PaddlePage;

/**
 * Node references page.
 */
class ReferencePage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'node/%/references';

    /**
     * {@inheritdoc}
     */
    public function waitUntilPageIsLoaded()
    {
        $this->webdriver->waitUntilElementIsDisplayed(
            '//body[contains(@class, "page-node-references")]'
        );
    }
}
