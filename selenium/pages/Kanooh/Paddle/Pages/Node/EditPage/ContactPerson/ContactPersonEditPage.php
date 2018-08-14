<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\ContactPerson\ContactPersonEditPage.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\ContactPerson;

use Kanooh\Paddle\Pages\Node\EditPage\EditPage;

/**
 * Page to edit a contact person.
 *
 * @property ContactPersonEditForm $form
 */
class ContactPersonEditPage extends EditPage
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'form':
                return new ContactPersonEditForm($this->webdriver, $this->webdriver->byId('contact-person-node-form'));
        }
        return parent::__get($property);
    }
}
