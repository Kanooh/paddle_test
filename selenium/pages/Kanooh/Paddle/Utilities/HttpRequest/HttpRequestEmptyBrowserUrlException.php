<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Utilities\HttpRequestEmptyBrowserUrlException.
 */

namespace Kanooh\Paddle\Utilities\HttpRequest;

/**
 * Exception for data in a bad format.
 */
class HttpRequestEmptyBrowserUrlException extends HttpRequestException
{

    /**
     * Constructor for the HttpRequestEmptyBrowserUrlException.
     */
    public function __construct()
    {
        $message = "The browser cannot execute javascript when it's not on a valid location.";
        parent::__construct($message);
    }
}
