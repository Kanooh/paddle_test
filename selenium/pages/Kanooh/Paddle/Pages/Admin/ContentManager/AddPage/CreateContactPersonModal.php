<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\CreateContactPersonModal.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\AddPage;

use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class representing the modal dialog for creating new contact persons.
 *
 * @package Kanooh\Paddle\Pages\Admin\ContentManager\AddPage
 *
 * @property Text $firstName
 *   The first name text field.
 * @property Text $lastName
 *   The last name text field.
 */
class CreateContactPersonModal extends CreateNodeModal
{
    /**
     * Magic getter.
     */
    public function __get($property)
    {
        switch ($property) {
            case 'firstName':
                return new Text($this->webdriver, $this->webdriver->byName('field_paddle_cp_first_name[und][0][value]'));
            case 'lastName':
                return new Text($this->webdriver, $this->webdriver->byName('field_paddle_cp_last_name[und][0][value]'));
        }

        return parent::__get($property);
    }
}
