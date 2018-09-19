<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\AutoComplete\AutoCompleteInvalidSuggestionValueException.
 */

namespace Kanooh\Paddle\Pages\Element\AutoComplete;

/**
 * Should be thrown when an invalid suggestion value is passed.
 */
class AutoCompleteInvalidSuggestionValueException extends AutoCompleteException
{
    /**
     * Constructor method.
     *
     * @param string $value
     *   Invalid suggestion value.
     */
    public function __construct($value = '')
    {
        parent::__construct('Invalid value "' . $value . '" passed as suggestion.');
    }
}
