<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\CalendarItemViewPage.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage;

/**
 * A calendar item detail page in the frontend view.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $icalLinkField
 *   The iCal feed link extra field.
 */
class CalendarItemViewPage extends ViewPage
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'icalLinkField':
                $xpath = '//div[@id="node-' . $this->getNodeId() . '"]//a[contains(@class, "ical-event")]';
                return $this->webdriver->byXPath($xpath);
                break;
        }
        return parent::__get($property);
    }
}
