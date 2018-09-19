<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\CalendarPanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Element;

/**
 * The 'Calendar' Panels content type.
 */
class CalendarPanelsContentType extends SectionedPanelsContentType
{

    /**
    * {@inheritdoc}
    */
    const TYPE = 'calendar';

    /**
    * {@inheritdoc}
    */
    const TITLE = 'Calendar';

    /**
    * {@inheritdoc}
    */
    const DESCRIPTION = 'Add a calendar.';

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
        if (!isset($this->form)) {
            $this->form = new CalendarPanelsContentTypeForm(
                $this->webdriver,
                $this->webdriver->byXpath('//form[contains(@id, "paddle-calendar-calendar-content-type-edit-form")]')
            );
        }

        return $this->form;
    }
}
