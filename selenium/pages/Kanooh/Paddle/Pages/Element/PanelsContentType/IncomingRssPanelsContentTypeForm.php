<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\IncomingRssPanelsContentTypeForm.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\Select;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class representing the Incoming RSS pane form.
 *
 * @property Select $incomingRssFeeds
 *   The select containing the Incoming RSS feeds, keyed by entity id.
 * @property RadioButton $titleViewMode
 *   The radiobutton for the "Titles only" view mode.
 * @property RadioButton $magazineViewMode
 *   The radiobutton for the "Magazine mode" view mode.
 * @property Text $numberOfItems
 *   The textbox containing the number of items to display.
 */
class IncomingRssPanelsContentTypeForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'incomingRssFeeds':
                return new Select($this->webdriver, $this->webdriver->byXPath('//select[@name="rss_list"]'));
            case 'titleViewMode':
                return new RadioButton($this->webdriver, $this->webdriver->byXpath('//input[@name="view_mode" and @value="title"]'));
            case 'magazineViewMode':
                return new RadioButton($this->webdriver, $this->webdriver->byXpath('//input[@name="view_mode" and @value="magazine"]'));
            case 'numberOfItems':
                return new Text($this->webdriver, $this->webdriver->byXPath('//input[@name="number_items"]'));
        }
        throw new FormFieldNotDefinedException($name);
    }
}
