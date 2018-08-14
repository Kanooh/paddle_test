<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleComment\CommentManagerPage\CommentManagerPageTable.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleComment\CommentManagerPage;

use \Kanooh\Paddle\Pages\Element\Table\Table;

class CommentManagerPageTable extends Table
{

    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//div[contains(@class,"view-comment-manager")]//table[contains(@class,"views-table")]';

    /**
     * Finds table <tr>s by the comment id in them and returns their Row object.
     *
     * @param int $cid
     *   The id of the comment. It will be used in the xpath to find the row.
     *
     * @return mixed
     *   The <tr> we are looking for, false otherwise.
     */
    public function getCommentRowByCid($cid)
    {
        $row_xpath = $this->xpathSelector . '//tr//td[contains(@class,"views-field-cid") and contains(concat(" ", text(), " "), " ' . $cid . ' ")]/..';
        $criteria = $this->webdriver->using('xpath')->value($row_xpath);
        $elements = $this->webdriver->elements($criteria);

        if (count($elements)) {
            $this->webdriver->waitUntilElementIsDisplayed($row_xpath);
            return new CommentManagerPageTableRow($this->webdriver, $row_xpath);
        }

        return false;
    }
}
