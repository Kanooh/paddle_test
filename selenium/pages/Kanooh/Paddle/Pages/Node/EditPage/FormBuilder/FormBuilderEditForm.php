<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\FormBuilder\FormBuilderEditForm.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\FormBuilder;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Wysiwyg\Wysiwyg;

/**
 * Class representing the FormBuilder edit form (of the node not the form).
 *
 * @property Wysiwyg $body
 */
class FormBuilderEditForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'body':
                return new Wysiwyg($this->webdriver, 'edit-body-und-0-value');
        }
        throw new FormFieldNotDefinedException($name);
    }
}
