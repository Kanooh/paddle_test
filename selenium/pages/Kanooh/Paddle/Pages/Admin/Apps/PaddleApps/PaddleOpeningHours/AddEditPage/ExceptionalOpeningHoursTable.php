<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleOpeningHours\AddEditPage\ExceptionalOpeningHoursTable.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleOpeningHours\AddEditPage;

use Kanooh\Paddle\Pages\Element\Table\Table;

/**
 * The exceptional opening hours table.
 *
 * @property ExceptionalOpeningHoursTableRow[] $rows
 */
class ExceptionalOpeningHoursTable extends Table
{
    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//table[@id="field-ous-exc-opening-hours-values"]';

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'rows':
                $rows = array();
                $xpath = $this->xpathSelector . '/tbody/tr';
                $elements = $this->getWebdriverElement()->elements($this->webdriver->using('xpath')->value($xpath));
                foreach ($elements as $element) {
                    $rows[] = new ExceptionalOpeningHoursTableRow($this->webdriver, $element);
                }
                return $rows;
                break;
        }
        throw new \Exception("The property with the name $name is not defined.");
    }
}
