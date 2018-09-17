<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\AutoComplete\AutoComplete.
 */

namespace Kanooh\Paddle\Pages\Element\AutoComplete;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Element\Element;
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

/**
 * An autocomplete element.
 */
class AutoComplete extends Element
{
    protected $xpathSelector = '//div[@id="autocomplete"]';

    /**
     * Returns a list of possible suggestions as text.
     *
     * @return array
     *   An array of autocomplete suggestions.
     */
    public function getSuggestions()
    {
        $suggestions = array();
        foreach ($this->getSuggestionsAsElements() as $element) {
            $suggestions[] = trim($element->text());
        }
        return $suggestions;
    }

    /**
     * Returns a list of possible suggestions as webdriver elements.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element[]
     *   An array of selenium webdriver elements.
     */
    public function getSuggestionsAsElements()
    {
        $element = $this->waitUntilDisplayed();
        $suggestion_elements = $element->elements($element->using('xpath')->value('.//li'));

        return $suggestion_elements;
    }

    /**
     * Picks a suggestion by its position in the list.
     *
     * @param int $position
     *   The position of the suggestion in the list. (Starting from 0)
     * @param bool $use_keyboard
     *   True to pick the suggestion using the keyboard. Default to false.
     *
     * @throws AutoCompleteInvalidPositionValueException
     *   Thrown when an invalid position is passed.
     */
    public function pickSuggestionByPosition($position = 0, $use_keyboard = false)
    {
        $suggestion_elements = $this->getSuggestionsAsElements();

        $position = intval($position);
        if ($position >= count($suggestion_elements) || $position < 0) {
            throw new AutoCompleteInvalidPositionValueException($position);
        }

        if ($use_keyboard) {
            $this->webdriver->keys(str_repeat(Keys::DOWN, $position + 1) . Keys::ENTER);
        } else {
            /** @var \PHPUnit_Extensions_Selenium2TestCase_Element $suggestion_element */
            $suggestion_element = $suggestion_elements[$position];
            $this->webdriver->clickOnceElementIsVisible($suggestion_element);
        }
    }

    /**
     * Picks a suggestion by its value.
     *
     * @param string $value
     *   Value of the suggestion.
     * @param bool $use_keyboard
     *   True to pick the suggestion using the keyboard. Default to false.
     *
     * @throws AutoCompleteInvalidSuggestionValueException
     *   Thrown when an invalid value is passed.
     */
    public function pickSuggestionByValue($value = '', $use_keyboard = false)
    {
        $position = array_search($value, $this->getSuggestions());

        if ($position === false) {
            throw new AutoCompleteInvalidSuggestionValueException($value);
        }

        $this->pickSuggestionByPosition($position, $use_keyboard);
    }

    /**
     * Waits until the AutoComplete dropdown is displayed, or times out.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function waitUntilDisplayed()
    {
        return $this->webdriver->waitUntilElementIsDisplayed($this->getXPathSelector());
    }

    /**
     * Waits until the AutoComplete dropdown is no longer displayed, or times out.
     */
    public function waitUntilNoLongerDisplayed()
    {
        $autocomplete = $this;
        $callable = new SerializableClosure(
            function () use ($autocomplete) {
                // Try to find the autocomplete.
                $criteria = $autocomplete->webdriver->using('xpath')->value($autocomplete->xpathSelector);
                $elements = $autocomplete->webdriver->elements($criteria);
                if (count($elements) == 0) {
                    return true;
                }
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }

    /**
     * Waits until the amount of suggestions equals the specified amount.
     *
     * This is useful if you want to loop over all suggestions and assert that
     * they are the same as the ones you were expecting, because on slower
     * machines the suggestions might lag a bit behind and be out-of-date after
     * updating the text field.
     */
    public function waitUntilSuggestionCountEquals($count)
    {
        $autocomplete = $this;
        $callable = new SerializableClosure(
            function () use ($count, $autocomplete) {
                if (count($autocomplete->getSuggestionsAsElements()) == $count) {
                    return true;
                }
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }
}
