<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\CreateNewsletterModal.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\AddPage;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class representing the modal dialog for creating new newsletters.
 *
 * @package Kanooh\Paddle\Pages\Admin\ContentManager\AddPage
 *
 * @property Text $title
 *   The title text field.
 * @property RadioButton $listOne
 *   The first test list radio button.
 * @property RadioButton $listTwo
 *   The second test list radio button.
 * @property Text $fromName
 *   The from name text field.
 * @property Text $fromEmail
 *   The from email text field.
 */
class CreateNewsletterModal extends CreateNodeModal
{
    /**
     * Magic getter.
     */
    public function __get($property)
    {
        switch ($property) {
            case 'title':
                return new Text($this->webdriver, $this->webdriver->byName('title'));
            case 'listOne':
                $xpath = '//label[contains(text(), "List One")]/../input[@type="radio"]';
                return new RadioButton($this->webdriver, $this->webdriver->byXPath($xpath));
            case 'listTwo':
                $xpath = '//label[contains(text(), "List Two")]/../input[@type="radio"]';
                return new RadioButton($this->webdriver, $this->webdriver->byXPath($xpath));
            case 'fromName':
                $name = 'field_paddle_mailchimp_cid[campaign_data][from_name]';
                return new Text($this->webdriver, $this->webdriver->byName($name));
            case 'fromEmail':
                $name = 'field_paddle_mailchimp_cid[campaign_data][from_email]';
                return new Text($this->webdriver, $this->webdriver->byName($name));
        }

        return parent::__get($property);
    }
}
