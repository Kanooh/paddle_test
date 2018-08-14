<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Content\ContentLockPage\ContentLockPageTable.
 */
namespace Kanooh\Paddle\Pages\Admin\Content\ContentLockPage;

use Kanooh\Paddle\Pages\Element\Table\Table;

/**
 * The table on the page that provides an overview of locked content.
 */
class ContentLockPageTable extends Table
{
    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//table[@id="content_lock"]/tbody';

    /**
     * Finds a table row by the node title and/or username in it.
     *
     * @param string $node_title
     *   Title of the node.
     *
     * @param string $username
     *   Name of the user owning the lock.
     *
     * @return null|ContentLockPageRow
     *   The row, or null if no matching row was found.
     */
    public function getLockRow($node_title, $username)
    {
        $row_xpath = $this->xpathSelector . '/tr/';

        if ($node_title) {
            $row_xpath .= 'td[1]/a/span[text()="' . $node_title . '"]/../../..';
        }

        if ($username) {
            $row_xpath .= 'td[2]/a/span[text()="' . $username . '"]/../../..';
        }

        // Verify a matching row actually exists.
        if ($this->getElementCountByXPath($row_xpath)) {
            $this->webdriver->element($this->webdriver->using('xpath')->value($row_xpath));
            return new ContentLockPageRow($this->webdriver, $row_xpath);
        }

        return null;
    }
}
