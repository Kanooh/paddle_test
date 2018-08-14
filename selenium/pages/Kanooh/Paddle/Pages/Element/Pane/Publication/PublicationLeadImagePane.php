<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\Publication\PublicationLeadImagePane.
 */

namespace Kanooh\Paddle\Pages\Element\Pane\Publication;

use Kanooh\Paddle\Pages\Element\Pane\Pane;

/**
 * Class for the Publication lead image content type.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $image
 */
class PublicationLeadImagePane extends Pane
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'image':
                return $this->getWebdriverElement()->byCssSelector('img');
        }

        throw new \Exception("Property with name $name not defined");
    }
}
