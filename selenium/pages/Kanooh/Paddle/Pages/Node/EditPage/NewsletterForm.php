<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\NewsletterForm.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Node\EditPage\NewsletterRadioButtons;

/**
 * Class representing the newsletter specific part of the node edit form.
 *
 * @property Text $title
 *   The title textfield.
 * @property NewsletterRadioButtons $listId
 *   The list id radio buttons.
 * @property Text $fromName
 *   The from name textfield.
 * @property Text $fromEmail
 *   The from email textfield.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $submit
 *   The submit button.
 */
class NewsletterForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'title':
                return new Text($this->webdriver, $this->webdriver->byName('title'));
            case 'listId':
                $name = 'div#edit-field-paddle-mailchimp-cid-campaign-data-list-id';
                return new NewsletterRadioButtons($this->webdriver, $this->webdriver->byCssSelector($name));
            case 'fromName':
                $name = 'field_paddle_mailchimp_cid[campaign_data][from_name]';
                return new Text($this->webdriver, $this->webdriver->byName($name));
            case 'fromEmail':
                $name = 'field_paddle_mailchimp_cid[campaign_data][from_email]';
                return new Text($this->webdriver, $this->webdriver->byName($name));
            case 'submit':
                return $this->element->byCssSelector('input.form-submit');
        }
        throw new FormFieldNotDefinedException($name);
    }
}
