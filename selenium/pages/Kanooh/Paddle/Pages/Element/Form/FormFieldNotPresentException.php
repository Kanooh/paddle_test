<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Form\FormFieldNotPresentException.
 */

namespace Kanooh\Paddle\Pages\Element\Form;

/**
 * Exception for a missing form field.
 */
class FormFieldNotPresentException extends \RuntimeException
{

    /**
     * Constructor for the FormFieldNotPresentException.
     *
     * @param string $name
     *   The machine name of the form field that is not present.
     */
    public function __construct($name)
    {
        $message = 'The form field with the name ' . $name . ' is not present.';
        parent::__construct($message);
    }
}
