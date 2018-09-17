<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\OutgoingRSS\RSSFeedSettingsForm.
 */

namespace Kanooh\Paddle\Pages\Element\OutgoingRSS;

use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\Select;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;

/**
 * The main form of the add/edit Outgoing RSS feed entities.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $saveButton
 * @property Text $title
 * @property Checkbox $basicPageCheckBox
 * @property Checkbox $landingPageCheckBox
 * @property Checkbox $newsItemCheckBox
 * @property Checkbox $overviewPageCheckBox
 * @property AutoCompletedText $filterTags
 * @property AutoCompletedText $filterTerms
 * @property Select $selectStyle
 */
class RSSFeedSettingsForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'saveButton':
                return $this->element->byXPath('.//input[contains(@id, "edit-save")]');
                break;
            case 'title':
                return new Text($this->webdriver, $this->element->byXPath('.//input[@name="title"]'));
                break;
            case 'basicPageCheckBox':
                $element = $this->element->byXPath('.//input[@name="content_types[basic_page]"]');
                return new Checkbox($this->webdriver, $element);
            case 'landingPageCheckBox':
                $element = $this->element->byXPath('.//input[@name="content_types[landing_page]"]');
                return new Checkbox($this->webdriver, $element);
            case 'newsItemCheckBox':
                $element = $this->element->byXPath('.//input[@name="content_types[news_item]"]');
                return new Checkbox($this->webdriver, $element);
            case 'overviewPageCheckBox':
                $element = $this->element->byXPath('.//input[@name="content_types[paddle_overview_page]"]');
                return new Checkbox($this->webdriver, $element);
            case 'filterTags':
                $element = $this->element->byXPath('.//input[@name="paddle_tags"]');
                return new AutoCompletedText($this->webdriver, $element);
            case 'filterTerms':
                $element = $this->element->byXPath('.//input[@name="paddle_general"]');
                return new AutoCompletedText($this->webdriver, $element);
            case 'selectStyle':
                return new Select($this->webdriver, $this->webdriver->byId('edit-image-style'));
        }
    }
}
