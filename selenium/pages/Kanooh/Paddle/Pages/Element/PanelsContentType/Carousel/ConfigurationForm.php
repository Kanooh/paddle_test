<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\Carousel\ConfigurationForm.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\Carousel;

use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\Select;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;

/**
 * Class representing the carousel pane form.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $addButton
 *   The button to add a new slide.
 * @property SlideForm[] $slides
 *   Array of slides in the form.
 * @property int $slideCount
 *   Number of slides in the form.
 * @property Checkbox $autoplay
 *   The checkbox to enable autoplay of the carousel.
 * @property Select $sliderSpeedDropdown
 *   The dropdown to select the slide speed of the carousel.
 */
class ConfigurationForm extends Form
{
    /**
     * XPath to get the add button.
     *
     * @var string
     */
    protected $addButtonXPath = './/input[@type="submit"][contains(@id, "edit-slides-add")]';

    /**
     * XPath to get all the slides in the form, except the placeholder message.
     *
     * @var string
     */
    protected $slidesXPath = './/table[@id="paddle-carousel-slides-table"]//td[not(contains(@class, "empty"))]/..';

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'addButton':
                $criteria = $this->element->using('xpath')->value($this->addButtonXPath);
                return $this->element->element($criteria);
                break;
            case 'slides':
                $criteria = $this->element->using('xpath')->value($this->slidesXPath);
                $elements = $this->element->elements($criteria);
                $slides = array();
                foreach ($elements as $element) {
                    $slide = new SlideForm($this->webdriver, $element);
                    $slides[$slide->uuid] = $slide;
                }
                return $slides;
                break;
            // Sometimes the slides property takes too long to get all slides
            // and instantiate SlideForm classes, so if you are adding or
            // removing a slide it might try to instantiate a SlideForm class
            // for an element that's no longer attached to the DOM. In those
            // cases you are probably waiting for the count of slides to change
            // so you should use this property instead which is faster.
            case 'slideCount':
                $criteria = $this->element->using('xpath')->value($this->slidesXPath);
                return count($this->element->elements($criteria));
                break;
            case 'autoplay':
                return new Checkbox($this->webdriver, $this->webdriver->byName('autoplay'));
            case 'sliderSpeedDropdown':
                return new Select($this->webdriver, $this->webdriver->byName('slider_speed'));
        }
        throw new FormFieldNotDefinedException($name);
    }

    /**
     * Moves a given slide to the position of another slide.
     *
     * @param SlideForm $source
     *   Slide to move up or down.
     * @param SlideForm $target
     *   Slide to which the other slide should be dragged.
     */
    public function dragSlideTo(SlideForm $source, SlideForm $target)
    {
        // Workaround: enlarge the viewport as much as possible.
        $this->webdriver->prepareSession()->currentWindow()->maximize();

        // Workaround: first move to target so there's more chance that the
        // target is visible when we want to move the source to it.
        $this->webdriver->moveto($target->dragHandle);

        // Drag and drop.
        $this->webdriver->moveto($source->dragHandle);
        $this->webdriver->buttondown();
        $this->webdriver->moveto($target->dragHandle);
        $this->webdriver->buttonup();
    }
}
