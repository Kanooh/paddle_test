<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\Carousel\Carousel
 */

namespace Kanooh\Paddle\Pages\Element\Pane\Carousel;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Element\Pane\Pane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\CarouselPanelsContentType;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class for a Panels pane with Ctools content type 'Carousel'.
 *
 * @property Slide[] $slides
 *   List of slides in the pane, keyed by their uuid.
 * @property int $currentSlideNumber
 *   Number of the current slide, as displayed on the carousel. (Starts at 1)
 * @property int $totalSlidesNumber
 *   Total number of slides, as displayed on the carousel.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $nextButton
 *   Button to the next slide.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $previousButton
 *   Button to the previous slide.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $playButton
 *   Button to start the carousel slideshow.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $pauseButton
 *   Button to pause the carousel slideshow.
 * @property int $sliderSpeed
 *   The speed with which the slider changes slides in miliseconds.
 */
class Carousel extends Pane
{

    /**
     * The object for the pane content type.
     *
     * @var CarouselPanelsContentType
     */
    public $contentType;

    /**
     * Constructs a Carousel pane.
     *
     * @param WebDriverTestCase $webdriver
     *   The webdriver object.
     * @param string $uuid
     *   The uuid of the pane.
     * @param string $pane_xpath_selector
     *   More general xpath selector for the pane.
     */
    public function __construct(WebDriverTestCase $webdriver, $uuid, $xpath_selector = '')
    {
        parent::__construct($webdriver, $uuid, $xpath_selector);
        $this->contentType = new CarouselPanelsContentType($this->webdriver);
    }

    /**
     * Returns a list of slides in the pane.
     *
     * @return Slide[]
     *   An array of slides, keyed by their uuid.
     */
    protected function getSlides()
    {
        $slides = array();

        $xpath = $this->getXPathSelectorByUuid() . '//div[contains(@class, "carousel")]//li[contains(@class, "slide") and not(contains(@class, "clone"))]';
        $criteria = $this->webdriver->using('xpath')->value($xpath);
        $elements = $this->webdriver->elements($criteria);

        /* @var \PHPUnit_Extensions_Selenium2TestCase_Element $element */
        foreach ($elements as $element) {
            $slide = new Slide($element);
            $slides[$slide->uuid] = $slide;
        }

        return $slides;
    }

    /**
     * Magic getter.
     */
    public function __get($property)
    {
        switch ($property) {
            case 'slides':
                return $this->getSlides();
                break;
            case 'currentSlideNumber':
                $xpath = $this->getXPathSelectorByUuid() . '//span[contains(@class, "current-slide")]';
                $element = $this->webdriver->byXPath($xpath);
                return (int) $element->text();
                break;
            case 'totalSlidesNumber':
                $xpath = $this->getXPathSelectorByUuid() . '//span[contains(@class, "total-slides")]';
                $element = $this->webdriver->byXPath($xpath);
                return (int) $element->text();
                break;
            case 'nextButton':
                $xpath = $this->getXPathSelectorByUuid() . '//a[contains(@class, "flex-next")]';
                return $this->webdriver->byXPath($xpath);
                break;
            case 'previousButton':
                $xpath = $this->getXPathSelectorByUuid() . '//a[contains(@class, "flex-prev")]';
                return $this->webdriver->byXPath($xpath);
                break;
            case 'playButton':
                $xpath = $this->getXPathSelectorByUuid() . '//i[contains(@class, "fa-play")]';
                return $this->webdriver->byXPath($xpath);
                break;
            case 'pauseButton':
                $xpath = $this->getXPathSelectorByUuid() . '//i[contains(@class, "fa-pause")]';
                return $this->webdriver->byXPath($xpath);
                break;
            case 'sliderSpeed':
                $xpath = $this->getXPathSelectorByUuid() . '//div[contains(@class, "carousel")]';
                $slider = $this->webdriver->byXPath($xpath);
                return (int) $slider->attribute('data-slider-speed');
                break;
        }
    }

    /**
     * Goes to the next slide and waits for the animation to finish.
     */
    public function nextSlide()
    {
        // Store the current slide number, as it will change when we navigate
        // to the next slide.
        $current_slide_number = $this->currentSlideNumber;

        $this->nextButton->click();
        $this->waitUntilSlideChanged($current_slide_number);
    }

    /**
     * Goes to the previous slide and waits for the animation to finish.
     */
    public function previousSlide()
    {
        // Store the current slide number, as it will change when we navigate
        // to the previous slide.
        $current_slide_number = $this->currentSlideNumber;

        $this->previousButton->click();
        $this->waitUntilSlideChanged($current_slide_number);
    }

    /**
     * Waits until the carousel animation to the next or previous slide is done.
     *
     * @param int $current_slide_number
     *   The number of the current slide.
     * @param int $custom_timeout
     *   A custom timeout to use to limit istead of the default.
     */
    public function waitUntilSlideChanged($current_slide_number, $custom_timeout = null)
    {
        $carousel = $this;
        $callable = new SerializableClosure(
            function () use ($carousel, $current_slide_number) {
                if ($carousel->currentSlideNumber != $current_slide_number) {
                    return true;
                }
            }
        );
        $timeout = $custom_timeout ? $custom_timeout : $this->webdriver->getTimeout();
        $this->webdriver->waitUntil($callable, $timeout);
    }

    /**
     * Waits until the slide counter is visible.
     */
    public function waitUntilSlideCounterVisible()
    {
        $xpath = $this->getXPathSelectorByUuid() . '//div[contains(@class, "slider-counter")]';
        $this->webdriver->waitUntilElementIsPresent($xpath);
    }

    /**
     * Waits until the wanted slide has been reached.
     *
     * @param int $slide_number
     *   The number of the slide to wait for.
     */
    public function waitUntilSlideReached($slide_number)
    {
        $carousel = $this;
        $callable = new SerializableClosure(
            function () use ($carousel, $slide_number) {
                if ($carousel->currentSlideNumber == $slide_number) {
                    return true;
                }
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }
}
