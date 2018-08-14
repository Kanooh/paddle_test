<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Node\RevisionsPage\RevisionsPage.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Node\RevisionsPage;

use Kanooh\Paddle\Pages\Element\Links\ContentAdminMenuLinks;
use Kanooh\Paddle\Pages\PaddlePage;

/**
 * Revisions page for a node.
 *
 * @property ContentAdminMenuLinks $adminMenuLinks
 *   The admin menu links.
 * @property RevisionsPageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property RevisionsPageTable $table
 *   The table containing the revisions.
 */
class RevisionsPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'node/%/moderation/diff';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'adminMenuLinks':
                return new ContentAdminMenuLinks($this->webdriver);
            case 'contextualToolbar':
                return new RevisionsPageContextualToolbar($this->webdriver);
            case 'table':
                return new RevisionsPageTable($this->webdriver);
        }
        return parent::__get($property);
    }
}
