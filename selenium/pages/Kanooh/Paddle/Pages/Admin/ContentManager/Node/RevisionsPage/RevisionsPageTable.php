<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Node\RevisionsPage\RevisionsPageTable.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Node\RevisionsPage;

use \Kanooh\Paddle\Pages\Element\Table\Table;

class RevisionsPageTable extends Table
{

    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//table[contains(@class,"diff-revisions")]';

    /**
     * Finds table <tr>s by the revision id in them and returns their Row object.
     *
     * @param int $vid
     *   The vid of the revision. It will be used in the xpath to find the node.
     *
     * @return mixed
     *   The <tr> we are looking for, false otherwise.
     */
    public function getRevisionRowByVid($vid)
    {
        $row_xpath = $this->xpathSelector . '//tr[@data-revision-id="' . $vid . '"]';
        $criteria = $this->webdriver->using('xpath')->value($row_xpath);
        $elements = $this->webdriver->elements($criteria);

        if (count($elements)) {
            $this->webdriver->waitUntilElementIsDisplayed($row_xpath);
            return new RevisionsPageTableRow($this->webdriver, $row_xpath);
        }

        return false;
    }
}
