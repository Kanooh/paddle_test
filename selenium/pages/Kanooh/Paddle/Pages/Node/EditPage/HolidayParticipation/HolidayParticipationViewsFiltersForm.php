<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\HolidayParticipation\HolidayParticipationViewsFiltersForm.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\HolidayParticipation;

use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Form\Checkboxes;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Select;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class representing the HolidayParticipation exposed filters on the views.
 *
 * @property Select $province
 * @property Checkbox $temporaryEvent
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $search
 * @property Checkboxes $formula
 * @property Checkboxes $roomBoard
 * @property Text $capacityRange
 * @property AutoCompletedText $region
 * @property Checkboxes $labels
 * @property Checkboxes $facilities
 * @property Select $year
 * @property Select $month
 * @property Text $title
 */
class HolidayParticipationViewsFiltersForm extends Form
{
    /**
     * {@inheritdoc}
     */

    public function __get($name)
    {
        switch ($name) {
            case 'province':
                $xpath = '//div[@class="view-filters"]//div[contains(@class, "form-item-field-hp-province-value")]//select';
                return new Select($this->webdriver, $this->element->byXPath($xpath));
            case 'temporaryEvent':
                try {
                    $xpath = '//div[@class="view-filters"]//div[contains(@class, "form-item-field-hp-contract-type-value-hp-evenement")]//input';
                    return new Checkbox($this->webdriver, $this->element->byXPath($xpath));
                } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
                    return false;
                }
                break;
            case 'search':
                $xpath = '//div[@class="view-filters"]//div[contains(@class, "search-button")]//input';
                return $this->element->byXPath($xpath);
                break;
            case 'formula':
                $xpath = '//div[@class="view-filters"]//div[contains(@class, "hp-formula-oh-filter")]//div[contains(@class ,"bef-checkboxes")]';
                return new Checkboxes($this->webdriver, $this->element->byXPath($xpath));
                break;
            case 'roomBoard':
                $xpath = '//div[@class="view-filters"]//div[contains(@class, "hp-room-board-filter")]//div[contains(@class ,"bef-checkboxes")]';
                return new Checkboxes($this->webdriver, $this->element->byXPath($xpath));
                break;
            case 'capacityRange':
                $xpath = '//div[@class="view-filters"]//div[contains(@class, "form-item-capacity-range")]//input';
                return new Text($this->webdriver, $this->element->byXPath($xpath));
                break;
            case 'region':
                $xpath = '//div[@class="view-filters"]//div[contains(@class, "form-item-field-geofield-distance-origin")]//input';
                return new AutoCompletedText($this->webdriver, $this->element->byXPath($xpath));
                break;
            case 'labels':
                $xpath = '//div[@class="view-filters"]//div[contains(@class, "hp-labels-filter")]//div[contains(@class ,"bef-checkboxes")]';
                return new Checkboxes($this->webdriver, $this->element->byXPath($xpath));
                break;
            case 'facilities':
                $xpath = '//div[@class="view-filters"]//div[contains(@class, "room-board-filter")]//div[contains(@class ,"bef-checkboxes")]';
                return new Checkboxes($this->webdriver, $this->element->byXPath($xpath));
                break;
            case 'year':
                $xpath = '//div[@class="view-filters"]//div[contains(@class, "form-item-contract-year")]//select';
                return new Select($this->webdriver, $this->element->byXPath($xpath));
                break;
            case 'month':
                $xpath = '//div[@class="view-filters"]//div[contains(@class, "form-item-month")]//select';
                return new Select($this->webdriver, $this->element->byXPath($xpath));
                break;
            case 'title':
                $xpath = '//div[@class="view-filters"]//div[contains(@class, "form-item-title")]//input';
                return new Text($this->webdriver, $this->element->byXPath($xpath));
                break;
        }

        throw new FormFieldNotDefinedException($name);
    }
}
