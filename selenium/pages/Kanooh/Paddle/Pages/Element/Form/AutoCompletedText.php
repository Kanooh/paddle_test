<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Form\AutoCompletedText.
 */

namespace Kanooh\Paddle\Pages\Element\Form;

/**
 * A form field representing a text auto complete input field.
 */
class AutoCompletedText extends Text
{
    /**
     * Fills in the text field with the given text.
     *
     * And wait until the Drupal auto complete element has finished ajaxing.
     *
     * @param string $text
     *   The text to use to fill in the field.
     */
    public function fill($text)
    {
        // Move the mouse to the element to ensure we can focus it.
        $this->webdriver->moveto($this->element);

        $this->clear();
        $this->markAsWaitingForAutoCompleteResults();
        $this->element->value($text);
        $this->waitForAutoCompleteResults();
    }

    /**
     * Wait until auto completion results have been retrieved.
     *
     * @see isWaitingForAutoCompleteResults()
     */
    public function waitForAutoCompleteResults()
    {
        $field = $this;

        $this->webdriver->waitUntil(
            function (\PHPUnit_Extensions_Selenium2TestCase $webdriver) use ($field) {
                if (!$field->isWaitingForAutoCompleteResults()) {
                    return true;
                }
            },
            $this->webdriver->getTimeout()
        );
    }

    /**
     * Marks the element as if it is waiting for auto completion results.
     *
     * Ongoing auto completions will cause a JavaScript alert box to pop up when
     * trying to submit forms or navigate away from the page, thus blocking
     * Selenium test scenario's. So whenever our test code triggers an
     * auto complete, it needs to explicitly wait for the auto complete to
     * finish as well before proceeding.
     *
     * Unfortunately, once it has been triggered, Drupal's auto completion
     * implementation does not seem to offer any means to outside code to
     * differentiate between
     * - an auto complete that still needs to start, and
     * - an auto complete that was started and also has finished already.
     *
     * However, Drupal's auto completion implementation does allow us to check
     * if it's in progress, because it adds a CSS class 'throbbing' to the
     * HTML input element right before ajaxing and removes it afterwards. See
     * Drupal.jsAC.prototype.setStatus().
     *
     * By adding the 'throbbing' class ourselves, before triggering the auto
     * completion, and thus 'fake' that it was started already, we can just
     * wait for the class to be removed to assure that the auto complete
     * has finished.
     *
     * @see isWaitingForAutoCompleteResults()
     */
    public function markAsWaitingForAutoCompleteResults()
    {
        $this->webdriver->execute(
            array(
                'script' => "arguments[0].className += ' throbbing';",
                'args' => array($this->element->toWebDriverObject()),
            )
        );
    }

    /**
     * Indicates if the element is waiting for auto completion results.
     *
     * This checks for the 'throbbing' class to be present on the HTML input
     * element.
     *
     * @return bool
     *   True if auto completion is in progress, false if not.
     *
     * @see markAsWaitingForAutoCompleteResults()
     */
    public function isWaitingForAutoCompleteResults()
    {
        $css_classes = explode(' ', $this->element->attribute('class'));
        return in_array('throbbing', $css_classes);
    }
}
