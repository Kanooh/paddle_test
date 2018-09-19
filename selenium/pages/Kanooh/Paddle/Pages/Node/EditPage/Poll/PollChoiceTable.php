<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\Poll\PollChoiceTable.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\Poll;

use Kanooh\Paddle\Pages\Element\Table\Table;
use Kanooh\WebDriver\WebdriverTestCase;

/**
 * Table containing the choices(answers) for a poll on the node edit page.
 *
 * @property PollChoiceTableRow[] $rows
 *   An array with all the choice rows.
 */
class PollChoiceTable extends Table
{
    /**
     * Construct a new PollChoiceTable.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium web driver test case.
     * @param string $xpath
     *   The xpath selector of the table.
     */
    public function __construct(WebDriverTestCase $webdriver, $xpath)
    {
        parent::__construct($webdriver);
        $this->xpathSelector = $xpath;
    }

    /**
     * Magic getter for the element's properties.
     *
     * @param string $name
     *   The name of the property we need.
     *
     * @return mixed
     *   The element found.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'rows':
                $rows = array();
                $xpath = $this->xpathSelector . '/tbody/tr';
                $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
                foreach ($elements as $element) {
                    $rows[] = new PollChoiceTableRow($this->webdriver, $element);
                }
                return $rows;
                break;
        }
        throw new \RuntimeException("The property with the name $name is not defined.");
    }
}
