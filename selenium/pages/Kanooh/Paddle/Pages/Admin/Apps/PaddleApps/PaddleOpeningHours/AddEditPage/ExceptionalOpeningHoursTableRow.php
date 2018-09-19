<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleOpeningHours\AddEditPage\ExceptionalOpeningHoursTableRow.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleOpeningHours\AddEditPage;

use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Table\Row;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Represents a table row of the exceptional opening hours.
 *
 * @property Text $startDate
 * @property Text $endDate
 * @property Text $description
 * @property ExceptionalOpeningHoursDaysFieldset $daysFieldset
 */
class ExceptionalOpeningHoursTableRow extends Row
{
    protected $element;

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver, $element)
    {
        parent::__construct($webdriver);

        $this->element = $element;
    }

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'startDate':
                return new Text($this->webdriver, $this->element->byName('field_ous_exc_opening_hours[und][0][field_ous_exc_oh_date][und][0][value][date]'));
            case 'endDate':
                return new Text($this->webdriver, $this->element->byName('field_ous_exc_opening_hours[und][0][field_ous_exc_oh_date][und][0][value2][date]'));
            case 'description':
                return new Text($this->webdriver, $this->element->byName('field_ous_exc_opening_hours[und][0][field_ous_exc_oh_description][und][0][value]'));
            case 'daysFieldset':
                return new ExceptionalOpeningHoursDaysFieldset($this->webdriver, $this->element->byXPath('.//fieldset[contains(@class, "group-ous-exc-oh-days")]'));
        }

        throw new \Exception("The property with the name $name is not defined.");
    }
}
