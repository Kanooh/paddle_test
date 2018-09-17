<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Content\ContentLockPage\ContentLockPage.
 */
namespace Kanooh\Paddle\Pages\Admin\Content\ContentLockPage;

use Kanooh\Paddle\Pages\PaddlePage;

/**
 * Page providing an overview of locked content.
 *
 * @property ContentLockPageTable $table
 *   The table containing info about the nodes locked by specific users.
 */
class ContentLockPage extends PaddlePage
{

    /**
     * {@inheritdoc}
     */
    protected $path = 'admin/content/content_lock';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'table':
                return new ContentLockPageTable($this->webdriver);
        }
        return parent::__get($property);
    }

    /**
     * Finds lock info rows matching the passed conditions.
     *
     * @param string $node_title
     *   Title of the node.
     *
     * @param string $username
     *   Name of the user owning the lock.
     *
     *  @return null|ContentLockPageRow
     *   The row, or null if no matching row was found.
     */
    public function findRows($node_title = null, $username = null)
    {
        return $this->table->getLockRow($node_title, $username);
    }

    /**
     * {@inheritdoc}
     */
    public function waitUntilPageIsLoaded()
    {
        $xpath = '//body[contains(concat(" ", normalize-space(@class), " "), " page-admin-content-content-lock ")]';
        $this->webdriver->waitUntilElementIsDisplayed($xpath);
    }
}
