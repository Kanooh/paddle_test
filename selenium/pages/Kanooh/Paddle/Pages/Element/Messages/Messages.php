<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Messages\Messages.
 */

namespace Kanooh\Paddle\Pages\Element\Messages;

use Kanooh\Paddle\Pages\Element\Element;

/**
 * Class representing Drupal messages displayed to the end user.
 */
class Messages extends Element
{
    protected $xpathSelector = '//div[@id="messages"]';

    /**
     * Get displayed status messages.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element[]
     */
    public function statusMessages()
    {
        $messages = $this->webdriver->elements(
            $this->webdriver->using('xpath')->value($this->xpathSelector . '/div[contains(@class, "status")]')
        );

        return $messages;
    }

    /**
     * Get displayed warning messages.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element[]
     */
    public function warningMessages()
    {
        $messages = $this->webdriver->elements(
            $this->webdriver->using('xpath')->value($this->xpathSelector . '/div[contains(@class, "warning")]')
        );

        return $messages;
    }

    /**
     * Get displayed error messages.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element[]
     */
    public function errorMessages()
    {
        $messages = $this->webdriver->elements(
            $this->webdriver->using('xpath')->value($this->xpathSelector . '/div[contains(@class, "error")]')
        );

        return $messages;
    }
}
