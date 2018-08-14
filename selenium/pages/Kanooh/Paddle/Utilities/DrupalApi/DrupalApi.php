<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Utilities\DrupalApi\DrupalApi.
 */

namespace Kanooh\Paddle\Utilities\DrupalApi;

use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Abstract base class for DrupalApi classes.
 *
 * @package Kanooh\Paddle\Utilities\DrupalApi
 */
abstract class DrupalApi
{

    /**
     * Reference to webdriver object.
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * Base URL of the Drupal site.
     * @var string
     */
    protected $baseUrl;

    /**
     * @param WebDriverTestCase $webdriver
     * @param string $base_url
     */
    public function __construct(WebDriverTestCase $webdriver, $base_url = '')
    {
        // Default to the base url provided by the PHPUnit Selenium driver.
        $base_url = $base_url ?: $webdriver->base_url;

        $this->webdriver = $webdriver;
        $this->baseUrl = $base_url;
    }

    /**
     * Sets the base URL of the Drupal site.
     *
     * @param string $base_url
     */
    public function setBaseUrl($base_url)
    {
        $this->baseUrl = $base_url;
    }

    /**
     * Returns the base URL of the Drupal site.
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }
}
