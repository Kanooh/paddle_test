<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Utilities\HttpRequest\HttpRequest.
 */

namespace Kanooh\Paddle\Utilities\HttpRequest;

use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class HttpRequest
 * @todo Add more (supported) HTTP methods, such as PUT, DELETE, ...
 * @package Kanooh\Paddle\Utilities\HttpRequest
 */
class HttpRequest
{

    /**
     * GET method.
     */
    const GET = 'GET';

    /**
     * POST method.
     */
    const POST = 'POST';

    /**
     * Reference to webdriver object.
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * HTTP request method.
     * @var string
     */
    protected $method;

    /**
     * Request URL.
     * @var string
     */
    protected $url;

    /**
     * Request data.
     * @var array
     */
    protected $data;

    /**
     * Request timeout.
     * @var int
     */
    protected $timeout;

    /**
     * @param WebDriverTestCase $webdriver
     * @param string $method
     * @param string $url
     * @param array $data
     * @param int $timeout
     */
    public function __construct(WebDriverTestCase $webdriver, $method = '', $url = '', $data = array(), $timeout = 5000)
    {
        $this->webdriver = $webdriver;
        $this->method = $method;
        $this->url = $url;
        $this->data = $data;
        $this->timeout = $timeout;
    }

    /**
     * Sets the HTTP method.
     *
     * @param string $method
     *
     * @throws HttpRequestBadMethodException
     *   Thrown if the specified method is not valid or not supported.
     */
    public function setMethod($method)
    {
        if (!in_array($method, $this->getSupportedMethods())) {
            throw new HttpRequestBadMethodException($method);
        }

        $this->method = $method;
    }

    /**
     * Returns the HTTP method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Returns a list of supported HTTP methods.
     *
     * @return array
     */
    public function getSupportedMethods()
    {
        return array(
          HttpRequest::GET,
          HttpRequest::POST,
        );
    }

    /**
     * Sets the request URL.
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Returns the request URL.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets the data array.
     *
     * @param array $data
     *
     * @throws HttpRequestBadDataException
     */
    public function setData($data)
    {
        if (!is_array($data)) {
            throw new HttpRequestBadDataException($data);
        }

        $this->data = $data;
    }

    /**
     * Returns the data array.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets the request timeout, in milliseconds.
     *
     * @param int $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = (int) $timeout;
    }

    /**
     * Returns the timeout integer (in milliseconds).
     *
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Performs the HTTP request.
     *
     * @return mixed $response
     *   Array with response data, or FALSE if the request failed or timed out.
     *
     * @throws HttpRequestEmptyBrowserUrlException
     *   Thrown if the browser's url is empty, and so it can't execute any
     *   javascript.
     */
    public function send()
    {
        // Check that the browser's url is not empty, because we wouldn't be
        // able to execute any javascript.
        $browser_url = $this->webdriver->url();
        if (empty($browser_url)) {
            throw new HttpRequestEmptyBrowserUrlException();
        }

        // Copy these variables, because we might need to modify them.
        $url = $this->url;
        $data = $this->data;

        // GET requests should append the data to the URL.
        if ($this->method == HttpRequest::GET) {
            // Get a possible existing query from the URL.
            $url_parts = parse_url($url);
            $query_string = isset($url_parts['query']) ? $url_parts['query'] : '';

            // Merge the existing query with the data array.
            $query_array = array();
            parse_str($query_string, $query_array);
            $data = array_merge($query_array, $data);

            // Replace the existing query with the new one.
            $url_parts['query'] = http_build_query($data);

            // In an ideal world we'd use http_build_url(), but this is only
            // available if PECL is installed.
            $url = $this->buildUrl($url_parts);

            // Empty the data array, because the data is now in the URL.
            $data = array();
        }

        // Convert the data in a query string.
        $data = !empty($data) ? http_build_query($data) : null;

        // Build the Javascript to execute.
        $js = '
        return (function(method, url, data) {
            // Keep the http request in the scope of this function.
            var http = new XMLHttpRequest();
            http.open(method, url, false);

            if (method != "GET") {
                http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                http.setRequestHeader("Content-length", data.length);
                http.setRequestHeader("Connection", "close");
            }

            http.send(data);

            return http;
        })(arguments[0], arguments[1], arguments[2]);';

        // Note that we can't use array keys in the args array. This would throw
        // an exception "java.util.HashMap cannot be cast to java.util.List".
        $response = (object) $this->webdriver->execute(
            array(
                'script' => $js,
                'args' => array($this->method, $url, $data),
            )
        );

        return $response;
    }

    /**
     * Simple replacement for http_build_url(), which is only available when
     * PECL is installed.
     *
     * @todo Replace this with a 3rd party library, like eg.
     * https://github.com/webignition/url. More can be found at
     * https://packagist.org/search/?q=url or Google.
     *
     * @param array $url_parts
     *   Array with url parts, as returned by parse_url().
     *
     * @return string
     *   Url parts merged into a valid url string.
     */
    protected function buildUrl($url_parts)
    {
        $url = '';

        if (!empty($url_parts['scheme'])) {
            $url .= $url_parts['scheme'] . '://';
        }

        if (!empty($url_parts['host'])) {
            $url .= $url_parts['host'];
        }

        if (!empty($url_parts['user'])) {
            $url .= $url_parts['user'];
            $url .= (!empty($url_parts['pass'])) ? $url_parts['pass'] : '';
            $url .= '@';
        }

        if (!empty($url_parts['port'])) {
            $url .= ':' . $url_parts['port'];
        }

        if (!empty($url_parts['path'])) {
            $url .= $url_parts['path'];
        }

        if (!empty($url_parts['query'])) {
            $url .= '?' . $url_parts['query'];
        }

        if (!empty($url_parts['fragment'])) {
            $url .= '#' . $url_parts['fragment'];
        }

        return $url;
    }
}
