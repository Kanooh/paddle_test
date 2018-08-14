<?php

/**
 * @file
 * Contains \Kanooh\TestDataProvider\UrlTestDataProvider.
 */

namespace Kanooh\TestDataProvider;

class UrlTestDataProvider extends TestDataProvider
{
    /**
     * {@inheritdoc}
     */
    public function getValidDataSet()
    {
        return array(
            'http://localhost',
            'http://127.0.0.1/',
            'https://twitter.com/',
            'https://www.google.com/',
            'https://www.google.be/q=i+pity+the+fool',
            'http://en.wikipedia.org/wiki/Uniform_resource_locator',
            'https://johndoe:hunter2@my-cloud-provider.net:8080/login.php?redirect=my%20dashboard#Login_Form',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getInvalidDataSet()
    {
        return array(
            'abc123',
            'twitter.com',
            'https:/www.google.com/',
            'https://www.goog_le.be/q=i+pity+the+fool',
        );
    }
}
