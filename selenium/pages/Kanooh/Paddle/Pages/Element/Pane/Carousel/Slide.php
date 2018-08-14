<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\Carousel\Slide
 */

namespace Kanooh\Paddle\Pages\Element\Pane\Carousel;

/**
 * Represents a single slide in a carousel pane.
 *
 * @property int $atomId
 *   Id of the atom in the slide.
 * @property mixed $caption
 *   Caption text for the slide, or false if no caption was found.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $image
 *   The image element in the slide.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $link
 *   The a tag element around the content of the slide.
 * @property string $uuid
 *   The uuid of the pane.
 */
class Slide
{
    /**
     * The HTML element of the slide itself.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * Constructs a new Slide object.
     *
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The HTML element of the slide itself.
     */
    public function __construct(\PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->element = $element;
    }

    /**
     * Magic getter for properties.
     *
     * @param string $property
     *   The property you want to retrieve.
     *
     * @return mixed
     *   The property's value.
     */
    public function __get($property)
    {
        switch ($property) {
            case 'atomId':
                return $this->element->attribute('data-atom-id');
                break;
            case 'caption':
                $criteria = $this->element->using('xpath')->value('.//div[contains(@class, "figcaption")]');
                $elements = $this->element->elements($criteria);
                if (!empty($elements)) {
                    /* @var \PHPUnit_Extensions_Selenium2TestCase_Element $element */
                    $element = $elements[0];
                    // For some reason text() tends to return an empty string
                    // here, but only sometimes.
                    return $element->attribute("innerHTML");
                }
                return false;
                break;
            case 'image':
                $criteria = $this->element->using('xpath')->value('.//img');
                $image = $this->element->element($criteria);
                return $image;
                break;
            case 'link':
                $criteria = $this->element->using('xpath')->value('.//a[contains(@class, "carousel-slide-link")]');
                $elements = $this->element->elements($criteria);
                return count($elements) ? $elements[0] : null;
                break;
            case 'uuid':
                return $this->element->attribute('data-slide-uuid');
                break;
        }
    }
}
