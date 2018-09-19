<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Quiz\View\QuizStartScreen.
 */

namespace Kanooh\Paddle\Pages\Element\Quiz\View;

/**
 * Class QuizStartScreen
 * @package Kanooh\Paddle\Pages\Element\Quiz\View
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $disclaimerCloseButton
 *   Button to close the disclaimer
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $disclaimerCloseLink
 *   Link to close the disclaimer.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $disclaimerLink
 *   Link to open the disclaimer.
 */
class QuizStartScreen extends QuizScreen
{
    /**
     * XPath selector for the disclaimer link.
     *
     * @var string
     */
    protected $disclaimerLinkXPathSelector = './/a[contains(@class, "paddle-quiz-disclaimer-link")]';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'disclaimerCloseButton':
                $xpath = './/input[@type="submit"][contains(@class, "paddle-quiz-disclaimer-close")]';
                return $this->element->byXPath($xpath);
            case 'disclaimerCloseLink':
                $xpath = './/a[contains(@class, "paddle-quiz-disclaimer-close")]';
                return $this->element->byXPath($xpath);
            case 'disclaimerLink':
                return $this->element->byXPath($this->disclaimerLinkXPathSelector);
        }
        return parent::__get($property);
    }

    /**
     * Checks whether or not the disclaimer link is present on the screen.
     *
     * @return boolean
     *   TRUE when present, FALSE if not.
     */
    public function isDisclaimerLinkPresent()
    {
        $criteria = $this->element->using('xpath')->value($this->disclaimerLinkXPathSelector);
        $elements = $this->element->elements($criteria);
        return !empty($elements);
    }

    /**
     * Waits until the disclaimer is visible.
     */
    public function waitUntilDisclaimerIsVisible()
    {
        $self = $this;
        $this->webdriver->waitUntil(function () use ($self) {
            // Note that we need to return null instead of false if the
            // disclaimer is not visible. If anything else than null is returned
            // the waitUntil() will stop waiting. See waitUntil() documentation.
            return ($self->isDisclaimerVisible()) ? true : null;
        }, $this->webdriver->getTimeout());
    }

    /**
     * Waits until the disclaimer is hidden.
     */
    public function waitUntilDisclaimerIsHidden()
    {
        $self = $this;
        $this->webdriver->waitUntil(function () use ($self) {
            // Note that we need to return null instead of false if the
            // disclaimer is visible. If anything else than null is returned
            // the waitUntil() will stop waiting. See waitUntil() documentation.
            return ($self->isDisclaimerVisible()) ? null : true;
        }, $this->webdriver->getTimeout());
    }

    /**
     * Checks whether or not the disclaimer is (100%) visible.
     *
     * @return bool
     *   True if the disclaimer if fully visible (opacity 1), false otherwise.
     */
    public function isDisclaimerVisible()
    {
        // We're using a data attribute to detect visibility because while
        // animating the opacity from 0 to 1 the disclaimer is technically
        // "visible" but some interactions such as clicking the close link will
        // work with a delay which might confuse our Selenium tests.
        $xpath = './/div[contains(@class, "paddle-quiz-disclaimer")][@data-visible="1"]';
        try {
            $disclaimer = $this->element->byXPath($xpath);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
