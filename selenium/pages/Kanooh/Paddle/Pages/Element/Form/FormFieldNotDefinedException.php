<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException.
 */

namespace Kanooh\Paddle\Pages\Element\Form;

/**
 * Exception for an undefined form field.
 */
class FormFieldNotDefinedException extends \RuntimeException
{

    /**
     * Constructor for the FormFieldNotDefinedException.
     *
     * @param string $name
     *   The machine name of the form field that is not defined.
     */
    public function __construct($name)
    {
        $message = 'The form field with the name ' . $name . ' is not defined.';
        parent::__construct($message);
    }
}
