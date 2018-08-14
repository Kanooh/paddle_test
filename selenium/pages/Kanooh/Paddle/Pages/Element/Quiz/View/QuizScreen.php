<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Quiz\View\QuizScreen.
 */

namespace Kanooh\Paddle\Pages\Element\Quiz\View;

use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for quiz screens. For some screens this class is sufficient.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $fullScreenCloseLink
 *   Link to close full screen mode.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $fullScreenOpenLink
 *   Link to open full screen mode.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $image
 *   Image element (if present).
 * @property string $message
 *   Message stripped of HTML (if present).
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $nextButton
 *   Button to navigate to the next screen (if present).
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $previousButton
 *   Button to navigate to the previous screen (if present).
 * @property string $subtitle
 *   Screen subtitle (if present).
 * @property string $title
 *   Screen title (if present).
 */
class QuizScreen
{
    /**
     * The webdriver test case object.
     *
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * Webdriver element of the screen.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Constructs a new QuizScreen object.
     *
     * @param WebDriverTestCase $webdriver
     *   Webdriver test case object.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   Element that represents the screen.
     */
    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->webdriver = $webdriver;
        $this->element = $element;
    }

    /**
     * Magic getter.
     */
    public function __get($property)
    {
        switch ($property) {
            case 'fullScreenCloseLink':
                $xpath = './/a[contains(@class, "paddle-quiz-full-screen-close")]';
                return $this->element->byXPath($xpath);
            case 'fullScreenOpenLink':
                $xpath = './/a[contains(@class, "paddle-quiz-full-screen-open")]';
                return $this->element->byXPath($xpath);
            case 'image':
                $xpath = './/div[contains(@class, "paddle-quiz-image")]/img';
                return $this->element->byXPath($xpath);
            case 'message':
                $xpath = './/div[contains(@class, "paddle-quiz-message")]';
                $element = $this->element->byXPath($xpath);
                return $element->text();
            case 'nextButton':
                $xpath = './/input[@type="submit"][contains(@class, "next-screen")]';
                return $this->element->byXPath($xpath);
            case 'previousButton':
                $xpath = './/input[@type="submit"][contains(@class, "previous-screen")]';
                return $this->element->byXPath($xpath);
            case 'subtitle':
                $xpath = './/h4[contains(@class, "paddle-quiz-subtitle")]';
                $element = $this->element->byXPath($xpath);
                return $element->text();
            case 'title':
                $xpath = './/h3[contains(@class, "paddle-quiz-title")]';
                $element = $this->element->byXPath($xpath);
                return $element->text();
        }
    }
}
