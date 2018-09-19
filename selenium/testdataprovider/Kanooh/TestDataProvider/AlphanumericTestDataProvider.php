<?php

/**
 * @file
 * Contains \Kanooh\TestDataProvider\AlphanumericTestDataProvider.
 */

namespace Kanooh\TestDataProvider;

use Drupal\Component\Utility\Random;

class AlphanumericTestDataProvider extends TestDataProvider
{
    /**
     * Returns a random value from the valid data set.
     *
     * @param int $length
     *   The number of characters used in each result.
     * @param bool $start_with_letter
     *   Whether or not the first character should be a lowercase letter.
     * @param bool $lowercase
     *   Whether or not only lowercase characters may be used.
     *
     * @return mixed
     *   A valid value.
     */
    public function getValidValue($length = 12, $start_with_letter = false, $lowercase = false)
    {
        return $this->generateRandomString($length, $start_with_letter, $lowercase);
    }

    /**
     * Returns an array of valid test data.
     *
     * @param int $count
     *   The number of results to return.
     * @param int $length
     *   The number of characters used in each result.
     * @param bool $start_with_letter
     *   Whether or not the first character should be a lowercase letter.
     * @param bool $lowercase
     *   Whether or not only lowercase characters may be used.
     *
     * @return array
     *   An array of valid test data.
     */
    public function getValidDataSet($count = 10, $length = 12, $start_with_letter = false, $lowercase = false)
    {
        $dataset = array();
        for ($i = 0; $i < $count; $i++) {
            $dataset[] = $this->generateRandomString($length, $start_with_letter, $lowercase);
        }
        return $dataset;
    }

    /**
     * {@inheritdoc}
     */
    public function getInvalidDataSet()
    {
        return array(
            'abc 123',
            'twitter.com',
            'what\'sup',
            'm&ms',
            'mediæval',
        );
    }

    /**
     * Returns a random alphanumeric string.
     *
     * @param int $length
     *   The length of the string to return.
     * @param bool $start_with_letter
     *   Start with a letter. By default; we don't care.
     * @param bool $lowercase
     *   Only lowercase characters. By default; we don't care.
     *
     * @return string
     *   The random string.
     */
    protected function generateRandomString($length = 12, $start_with_letter = false, $lowercase = false)
    {
        if ($start_with_letter) {
            $first_letter = chr(97 + mt_rand(0, 25));
            $length--;
        } else {
            $first_letter = '';
        }
        $random = new Random();
        $string = $first_letter . $random->name($length);
        return $lowercase ? strtolower($string) : $string;
    }

    /**
     * Returns a random integer.
     *
     * @param int $min
     *   The lowest value to return.
     * @param int $max
     *   The highest value to return.
     *
     * @return int
     *   Random integer between $min and $max (inclusive).
     */
    public function generateRandomInteger($min, $max)
    {
        return rand($min, $max);
    }

    /**
     * Returns a random value from the valid data set split by spaces.
     *
     * @param int $length
     *   The number of characters used in each result.
     * @param int $min_word_length
     *   The minimum number of characters in a word.
     * @param int $max_word_length
     *   The maximum number of characters in a word.
     * @param bool $start_with_letter
     *   Whether or not the first character should be a lowercase letter.
     * @param bool $lowercase
     *   Whether or not only lowercase characters may be used.
     *
     * @return mixed
     *   A valid value.
     */
    public function getValidWordsValue(
        $length = 12,
        $min_word_length = 1,
        $max_word_length = 10,
        $start_with_letter = false,
        $lowercase = false
    ) {
        $string = $this->generateRandomString($length, $start_with_letter, $lowercase);

        if ($max_word_length < $length) {
            $position = 0;
            while ($position < $length) {
                $position += rand($min_word_length, $max_word_length);
                if (isset($string[$position]) && $string[$position - 1] != ' ') {
                    $string[$position] = ' ';
                }
            }
        }
        return $string;
    }
}
