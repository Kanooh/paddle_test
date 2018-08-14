<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\InvalidHTMLException.
 */

namespace Kanooh\Paddle\Pages\Element;

/**
 * Exception thrown when the HTML source is invalid.
 */
class InvalidHTMLException extends \RuntimeException
{

    /**
     * Constructor for the InvalidHTMLException.
     *
     * @param string $url
     *   The browser url where the invalid HTML is encountered.
     * @param array $errors
     *   An array of LibXMLError errors.
     */
    public function __construct($url = '', array $errors = array())
    {
        $message = 'Invalid HTML found on URL: ' . $url . "\n";
        $message .= "Errors:\n";
        foreach ($errors as $error) {
            /* @var $error \LibXMLError */
            $message .= $error->message;
        }
        parent::__construct($message);
    }
}
