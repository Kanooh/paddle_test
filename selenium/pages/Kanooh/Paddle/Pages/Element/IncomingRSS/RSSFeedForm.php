<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\IncomingRSS\RSSFeedForm.
 */

namespace Kanooh\Paddle\Pages\Element\IncomingRSS;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * The main form of the add/edit Incoming RSS feed entities.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $saveButton
 *   The form's save button.
 * @property Text $title
 *   The form's title field.
 * @property Text $url
 *   The rss url field.
 */
class RSSFeedForm extends Form
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
            case 'url':
                return new Text(
                    $this->webdriver,
                    $this->element->byXPath('.//input[@name="feeds[FeedsHTTPFetcher][source]"]')
                );
                break;
        }
        throw new FormFieldNotDefinedException($name);
    }
}
