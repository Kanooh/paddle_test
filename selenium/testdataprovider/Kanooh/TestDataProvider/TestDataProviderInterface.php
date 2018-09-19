<?php


/**
 * @file
 * Contains \Kanooh\TestDataProvider\TestDataProviderInterface.
 */

namespace Kanooh\TestDataProvider;

/**
 * Interface for TestDataProvider classes.
 */
interface TestDataProviderInterface
{
    /**
     * Returns an array of valid test data.
     *
     * @return array
     *   An array of valid test data.
     */
    public function getValidDataSet();

    /**
     * Returns an array of invalid test data.
     *
     * @return array
     *   An array of invalid test data.
     */
    public function getInvalidDataSet();

    /**
     * Returns a random value from the valid data set.
     *
     * @return mixed
     *   A valid value.
     */
    public function getValidValue();

    /**
     * Returns a random value from the invalid data set.
     *
     * @return mixed
     *   An invalid value.
     */
    public function getInvalidValue();
}
