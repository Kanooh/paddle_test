<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Utilities\DrupalApi\DrupalAjaxApi.
 */

namespace Kanooh\Paddle\Utilities\DrupalApi;

/**
 * Utility class to interact with the Drupal ajax library.
 */
class DrupalAjaxApi extends DrupalApi
{
    /**
     * Wait until the Drupal ajax handler on an element has finished with
     * ajaxing.
     *
     * @param string|\PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   HTML id of the element, or an element instance.
     * @param int $timeout
     *   Timeout, in milliseconds.
     */
    public function waitUntilElementFinishedAjaxing($element, $timeout = 30000)
    {
        if ($element instanceof \PHPUnit_Extensions_Selenium2TestCase_Element) {
            $element = $element->attribute('id');
        }

        $this->webdriver->waitUntil(
            function (\PHPUnit_Extensions_Selenium2TestCase $webdriver) use ($element) {
                $ajaxing = $webdriver->execute(
                    array(
                        'script' => "return Drupal.ajax[arguments[0]] && Drupal.ajax[arguments[0]].ajaxing",
                        'args' => array($element),
                    )
                );

                // During slow test runs the element may have already finished
                // ajaxing before it is first polled. In this case the script
                // will return null.
                if (false === $ajaxing || null === $ajaxing) {
                    return true;
                }
            },
            $timeout
        );
    }
}
