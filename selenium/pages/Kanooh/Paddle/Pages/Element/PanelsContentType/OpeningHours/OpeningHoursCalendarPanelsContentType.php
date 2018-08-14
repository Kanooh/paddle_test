<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\OpeningHours\OpeningHoursCalendarPanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\OpeningHours;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\PanelsContentType\SectionedPanelsContentType;

/**
 * The 'Opening Hours calendar' Panels content type.
 */
class OpeningHoursCalendarPanelsContentType extends SectionedPanelsContentType
{

    /**
     * {@inheritdoc}
     */
    const TYPE = 'opening_hours_calendar';

    /**
     * {@inheritdoc}
     */
    const TITLE = 'Opening hours calendar';

    /**
     * {@inheritdoc}
     */
    const DESCRIPTION = 'Add an opening hours calendar.';

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
    public function getForm()
    {
        if (!isset($this->form)) {
            $this->form = new ConfigurationForm(
                $this->webdriver,
                $this->webdriver->byId('paddle-opening-hours-opening-hours-calendar-content-type-edit-form')
            );
        }

        return $this->form;
    }

    /**
     * Adds a new node to the content type's configuration form.
     */
    public function addNode()
    {
        $form = $this->getForm();
        $current_amount = count($form->openingHoursListNode);

        $content_type = $this;

        $form->addButton->click();
        $callable = new SerializableClosure(
            function () use ($content_type, $current_amount) {
                // Make sure to always get a new instance of the form, as it may
                // have been rebuilt.
                $form = $content_type->getForm();
                if (count($form->openingHoursListNode) == $current_amount + 1) {
                    return true;
                }
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());
    }
}
