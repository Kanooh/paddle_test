<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Modal\Modal.
 */

namespace Kanooh\Paddle\Pages\Element\Modal;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\WebDriver\WebDriverTestCase;
use PHPUnit_Extensions_Selenium2TestCase_Keys as Keys;

/**
 * Base class for modal dialogs.
 */
abstract class Modal extends Element
{
    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//div[contains(concat(" ", normalize-space(@class), " "), " active-modal ")]';

    /**
     * The XPath selector that identifies the modal overlay.
     */
    protected $overlayXPathSelector = '//div[contains(concat(" ", normalize-space(@class), " "), " active-backdrop ")]';

    /**
     * The XPath selector that identifies the close button.
     */
    protected $closeButtonXPathSelector = '//div[contains(@class, "modal-header")]/a[contains(@class, "close")]';

    /**
     * The XPath selector that identifies the submit button.
     */
    protected $submitButtonXPathSelector = '//input[@type="submit"]';

    /**
     * Unique ID of the modal content.
     */
    protected $uniqueContentId = '';

    /**
     * Unique ID of the modal backdrop.
     */
    protected $uniqueBackdropId = '';

    /**
     * {@inheritdoc}
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        parent::__construct($webdriver);

        // Make the selectors for the close and submit buttons more specific.
        $this->closeButtonXPathSelector = $this->xpathSelector . $this->closeButtonXPathSelector;
        $this->submitButtonXPathSelector = $this->xpathSelector . $this->submitButtonXPathSelector;
    }

    /**
     * Makes the browser wait until the modal is fully loaded.
     *
     * This is determined by the fact that the submit button is displayed.
     */
    public function waitUntilOpened()
    {
        $this->webdriver->waitUntilElementIsDisplayed($this->submitButtonXPathSelector);

        // Store the modal's unique ids for later use.
        $this->getUniqueIds();
    }

    /**
     * Makes the browser wait until the modal backdrop is closed.
     *
     * This is determined by the fact that the modal overlay is no longer
     * present or no longer displayed.
     */
    public function waitUntilClosed()
    {
        $webdriver = $this->webdriver;
        $content_xpath = '//div[@id="' . $this->uniqueContentId . '"]';
        $backdrop_xpath = '//div[@id="' . $this->uniqueBackdropId . '"]';

        $callable = new SerializableClosure(
            function () use ($webdriver, $content_xpath) {
                try {
                    $webdriver->byXPath($content_xpath);
                } catch (\Exception $e) {
                    // Modal backdrop not present.
                    return true;
                }
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());

        $callable = new SerializableClosure(
            function () use ($webdriver, $backdrop_xpath) {
                try {
                    $webdriver->byXPath($backdrop_xpath);
                } catch (\Exception $e) {
                    // Modal backdrop not present.
                    return true;
                }
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }

    /**
     * Wait until the previous modal version is gone and fetch the new one.
     */
    public function waitUntilReloaded()
    {
        $this->waitUntilClosed();
        $this->waitUntilOpened();

        // Empty the modal's unique ids so getUniqueIds() will update them.
        $this->uniqueContentId = $this->uniqueBackdropId = '';
        $this->getUniqueIds();
    }

    /**
     * Closes a modal dialog without saving it.
     */
    public function close()
    {
        // Pressing escape key closes the modal.
        // This is by far the easiest way. 
        $this->webdriver->keys(Keys::ESCAPE);

    }

    /**
     * Submits the form in a modal dialog.
     */
    public function submit()
    {
        // Before submitting the modal, get its unique ids from the DOM so we
        // can use them to wait for the modal to close.
        $this->getUniqueIds();

        $criteria = $this->webdriver->using('xpath')->value($this->submitButtonXPathSelector);
        $submit_button = $this->webdriver->element($criteria);

        $this->webdriver->moveto($submit_button);
        $webdriver = $this->webdriver;

        // In some cases the button is clicked yet does not trigger an actual submit.
        // Added his so it keeps scrolling down and clicking until submit buttons is actually clicked and
        // and thus disappears.
        $callable = new SerializableClosure(
            function () use ($submit_button, $webdriver) {
              try {
                $webdriver->execute(
                    array(
                        'script' => "scrollBy(0, - window.innerHeight/2);",
                        'args' => array(),
                    )
                );
                // Try to click on the element again.
                $submit_button->click();
                return $submit_button->displayed() ? null : true;

              } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {

                // If we run into any error means it was clicked or no longer able to be clicked.
                return true;
              }
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());

        // It is possible that an alert window pops up when submitting a modal form.
        try {
            $this->webdriver->acceptAlert();
        } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            // no alert window is shown.
        }

        // When clicking the submit button in a modal, either the modal is
        // closed or rebuilt. Either way, wait until the old button is stale
        // or not visible anymore, so we are sure that if the modal was rebuilt,
        // it's the new one. The element will never be stale but just not visible
        // anymore for CKEditor modals.
        $callable = new SerializableClosure(
            function () use ($submit_button) {
                try {
                    return $submit_button->displayed() ? null : true;
                } catch (\PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
                    // Modal has been submitted.
                    return true;
                }
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }

    /**
     * Retrieves the unique ids of the modal from the DOM and stores them.
     */
    protected function getUniqueIds()
    {
        if (empty($this->uniqueContentId)) {
            $modal = $this->webdriver->byXPath($this->xpathSelector);
            $this->uniqueContentId = $modal->attribute('id');
        }

        if (empty($this->uniqueBackdropId)) {
            $backdrop = $this->webdriver->byXPath($this->overlayXPathSelector);
            $this->uniqueBackdropId = $backdrop->attribute('id');
        }
    }
}
