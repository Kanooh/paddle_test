<?php

/**
 * @file
 * Contains \Kanooh\TestDataProvider\TestDataProvider.
 */

namespace Kanooh\TestDataProvider;

/**
 * Base class for TestDataProvider classes.
 */
abstract class TestDataProvider implements TestDataProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getValidValue()
    {
        $values = $this->getValidDataSet();
        return $values[array_rand($values)];
    }

    /**
     * {@inheritdoc}
     */
    public function getInvalidValue()
    {
        $values = $this->getInvalidDataSet();
        return $values[array_rand($values)];
    }
}
