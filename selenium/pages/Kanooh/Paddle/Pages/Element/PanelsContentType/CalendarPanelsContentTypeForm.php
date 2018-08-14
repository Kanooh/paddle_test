<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\CalendarPanelsContentTypeForm.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Form\Checkboxes;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButton;

/**
 * Class representing the Calendar pane form.
 *
 * @property Checkboxes $calendarTags
 * @property RadioButton $monthCalendarViewMode
 * @property RadioButton $monthListViewMode
 * @property RadioButton $monthListImageViewMode
 * @property RadioButton $weekListViewMode
 */
class CalendarPanelsContentTypeForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'calendarTags':
                return new Checkboxes($this->webdriver, $this->webdriver->byXPath('//div[@id="edit-calendar-tags"]'));
            case 'monthCalendarViewMode':
                $xpath = '//input[@name="view_mode" and @value="month_calendar_view"]';
                return new RadioButton($this->webdriver, $this->webdriver->byXpath($xpath));
            case 'monthListViewMode':
                $xpath = '//input[@name="view_mode" and @value="month_list_view"]';
                return new RadioButton($this->webdriver, $this->webdriver->byXpath($xpath));
            case 'monthListImageViewMode':
                $xpath = '//input[@name="view_mode" and @value="month_list_view_image"]';
                return new RadioButton($this->webdriver, $this->webdriver->byXpath($xpath));
            case 'weekListViewMode':
                $xpath = '//input[@name="view_mode" and @value="week_list_view"]';
                return new RadioButton($this->webdriver, $this->webdriver->byXpath($xpath));
        }
        throw new FormFieldNotDefinedException($name);
    }
}
