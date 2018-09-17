<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\CarouselPanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\PanelsContentType\Carousel\ConfigurationForm;

/**
 * The 'Carousel' Panels content type.
 */
class CarouselPanelsContentType extends SectionedPanelsContentType
{

    /**
     * {@inheritdoc}
     */
    const TYPE = 'carousel';

    /**
     * {@inheritdoc}
     */
    const TITLE = 'Carousel';

    /**
     * {@inheritdoc}
     */
    const DESCRIPTION = 'Add a carousel.';

    /**
     * The slides to show.
     *
     * @var array
     *   Array of slides. Each slide is an associative array with the following
     *   keys:
     *   - image: Atom id of the slide image.
     */
    public $slides;

    /**
     * {@inheritdoc}
     */
    public function fillInConfigurationForm(Element $element = null)
    {
        $this->fillInSections();
    }

    /**
     * {@inheritdoc}
     */
    public function getForm(Element $element = null)
    {
        // Always create a new instance, as the form might be rebuilt at some
        // point. For the same reason we don't do a hard check on the id, as
        // it might get a suffix after rebuilding.
        return new ConfigurationForm(
            $this->webdriver,
            $this->webdriver->byXPath('//form[contains(@id, "paddle-carousel-carousel-content-type-edit-form")]')
        );
    }

    /**
     * Adds a new slide to the content type's configuration form.
     */
    public function addSlide()
    {
        $form = $this->getForm();
        $current_amount = $form->slideCount;

        $content_type = $this;

        $form->addButton->click();
        $callable = new SerializableClosure(
            function () use ($content_type, $current_amount) {
                // Make sure to always get a new instance of the form, as it may
                // have been rebuilt.
                $form = $content_type->getForm();
                if ($form->slideCount == $current_amount + 1) {
                    return true;
                }
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }

    /**
     * Removes a slide from the content type's configuration form.
     *
     * @param int $index
     *   Index number of the slide.
     */
    public function removeSlide($index)
    {
        $form = $this->getForm();
        $current_amount = $form->slideCount;

        $slides = array_values($form->slides);
        $slides[$index]->removeButton->click();

        $content_type = $this;

        $callable = new SerializableClosure(
            function () use ($content_type, $current_amount) {
                // Make sure to always get a new instance of the form, as it may
                // have been rebuilt.
                $form = $content_type->getForm();
                if ($form->slideCount == $current_amount - 1) {
                    return true;
                }
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }
}
