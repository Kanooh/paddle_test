<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\Product\ProductLeadImagePane.
 */

namespace Kanooh\Paddle\Pages\Element\Pane\Product;

use Kanooh\Paddle\Pages\Element\Pane\Pane;

/**
 * Class for the Product lead image content type.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $image
 */
class ProductLeadImagePane extends Pane
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
