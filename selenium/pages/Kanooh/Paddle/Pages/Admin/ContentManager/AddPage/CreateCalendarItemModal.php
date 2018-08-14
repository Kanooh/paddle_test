<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\CreateCalendarItemModal.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\AddPage;

use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class representing the modal dialog for creating new calendar items.
 *
 * @package Kanooh\Paddle\Pages\Admin\ContentManager\AddPage
 *
 * @property Text $title
 *   The title text field.
 */
class CreateCalendarItemModal extends CreateNodeModal
{
    /**
     * Magic getter.
     */
    public function __get($property)
    {
        switch ($property) {
            case 'title':
                return new Text($this->webdriver, $this->webdriver->byName('title'));
        }

        return parent::__get($property);
    }
}
