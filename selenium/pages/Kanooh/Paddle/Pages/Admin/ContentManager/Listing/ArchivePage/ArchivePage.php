<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Listing\ArchivePage\ArchivePage.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Listing\ArchivePage;

use Kanooh\Paddle\Pages\AdminPage;

/**
 * Class ArchivePage
 *
 * @property ArchivePageContentTable $contentTable
 * @property ArchivePageBulkActions $bulkActions
 */
class ArchivePage extends AdminPage
{
    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/content_manager/archive';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contentTable':
                return new ArchivePageContentTable($this->webdriver);
            case 'bulkActions':
                return new ArchivePageBulkActions($this->webdriver, $this->webdriver->byId('views-form-paddle-archive-paddle-content-manager-archive'));
        }
        return parent::__get($property);
    }

    /**
     * {@inheritdoc}
     */
    public function waitUntilPageIsLoaded()
    {
        $this->webdriver->waitUntilElementIsDisplayed(
            '//body[contains(@class, "page-admin-content-manager-archive")]'
        );
    }
}
