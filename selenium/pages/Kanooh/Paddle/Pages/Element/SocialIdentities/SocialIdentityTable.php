<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\SocialIdentities\SocialIdentityTable.
 */

namespace Kanooh\Paddle\Pages\Element\SocialIdentities;

use \Kanooh\Paddle\Pages\Element\Table\Table;

/**
 * Class representing the table in Social Identity entity add/edit form.
 */
class SocialIdentityTable extends Table
{

    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//table[contains(@id, "field-social-identity-urls-values")]';

    /**
     * Returns the row found on the passed position.
     *
     * @param int $position
     *   The position of the row starting from 0.
     *
     * @return SocialIdentityTableRow | null
     *   The row of null if it doesn't exist.
     */
    public function getRowByPosition($position)
    {
        $xpath = $this->xpathSelector . '/tbody/tr';
        $rows = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
        return isset($rows[$position]) ? new SocialIdentityTableRow($this->webdriver, $rows[$position]) : null;
    }

    /**
     * @{@inheritdoc}
     */
    public function getNumberOfRows()
    {
        // In our case we want only the rows which are in the table body.
        $xpath = $this->xpathSelector . '/tbody/tr';
        $rows = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
        return is_array($rows) ? count($rows) : 0;
    }
}
