<?php

/**
 * @file
 * Contains \Kanooh\TestDataProvider\EmailTestDataProvider.
 */

namespace Kanooh\TestDataProvider;

use Drupal\Component\Utility\Random;

class EmailTestDataProvider extends TestDataProvider
{
    /**
     * {@inheritdoc}
     */
    public function getValidDataSet()
    {
        return array(
            'info@kanooh.be',
            'noname.really@domain.com',
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
        );
    }

    /**
     * Generate a random valid email address.
     */
    public function getValidValue()
    {
        $random = new Random();
        return $random->name(12, true) . '@kanooh.be';
    }
}
