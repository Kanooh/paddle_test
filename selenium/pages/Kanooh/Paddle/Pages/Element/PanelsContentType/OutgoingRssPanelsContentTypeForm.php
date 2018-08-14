<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\OutgoingRssPanelsContentTypeForm.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Checkbox;

/**
 * Class representing the Outgoing RSS feed list pane form.
 *
 * @property Checkbox[] $rssFeeds
 *   The checkboxes to select a RSS feed, keyed by entity id.
 */
class OutgoingRssPanelsContentTypeForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'rssFeeds':
                $criteria = $this->element->using('xpath')->value('.//input[@type="checkbox"][contains(@name, "outgoing_rss_feeds")]');
                $elements = $this->element->elements($criteria);
                $checkboxes = array();

                /* @var \PHPUnit_Extensions_Selenium2TestCase_Element $element */
                foreach ($elements as $element) {
                    // Key the array by entity id so we can easily target them.
                    $checkboxes[$element->attribute('value')] = new Checkbox($this->webdriver, $element);
                }
                return $checkboxes;
        }
        throw new FormFieldNotDefinedException($name);
    }
}
