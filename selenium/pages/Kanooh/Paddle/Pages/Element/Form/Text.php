<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Form\Text.
 */

namespace Kanooh\Paddle\Pages\Element\Form;

/**
 * A form field representing a text input field.
 */
class Text extends FormField
{

    /**
     * Clears the text field.
     */
    public function clear()
    {
        $this->element->clear();
    }

    /**
     * Fills in the text field with the given text.
     *
     * @param string $text
     *   The text to use to fill in the field.
     */
    public function fill($text)
    {
        $this->clear();
        $this->element->value($text);
    }

    /**
     * Returns the content of the field.
     *
     * @return string
     *   The content of the field.
     */
    public function getContent()
    {
        return $this->element->attribute('value');
    }

    public function fillOnceVisible($text) {
      // First assert if we can move to the element, so we are sure
      // that the element exists.
      $this->webdriver->moveto($this->element);

      try {
        // Click on the element.
        $this->fill($text);
      } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
        // The element is not visible:
        // Scroll so that the element is shown in the center of your
        // screen.
        $this->webdriver->execute(
            array(
              'script' => "scrollBy(0, - window.innerHeight/2);",
              'args' => array(),
           )
         );
         // Try to click on the element again.
         $this->fill($text);
    }
  }
}
