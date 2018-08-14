<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Utilities\HttpRequestBadMethodException.
 */

namespace Kanooh\Paddle\Utilities\HttpRequest;

/**
 * Exception for invalid or unsupported HTTP methods.
 */
class HttpRequestBadMethodException extends HttpRequestException
{

    /**
     * Constructor for the HttpRequestBadMethodException.
     *
     * @param string $method
     *   The invalid or unsupported method name.
     */
    public function __construct($method)
    {
        $message = 'The ' . $method . ' method is invalid or unsupported.';
        parent::__construct($message);
    }
}
