<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\OpeningHours\OpeningHoursListNode.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\OpeningHours;

use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\Paddle\Pages\Element\Form\Form;

/**
 * The autocomplete field and the remove button for each row.
 *
 * @property AutoCompletedText $node
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $remove
 */
class OpeningHoursListNode extends Form
{

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'node':
                return new AutoCompletedText($this->webdriver, $this->webdriver->byXPath('.//div[contains(@class, "form-type-textfield")]/input[contains(@id, "edit-nodes-list")]'));
                break;
            case 'remove':
                $criteria = $this->element->using('xpath')->value('.//input[contains(@value, "Remove page")]');
                return $this->element->element($criteria);
                break;
        }

        throw new \Exception("Property with name $name not found");
    }
}
