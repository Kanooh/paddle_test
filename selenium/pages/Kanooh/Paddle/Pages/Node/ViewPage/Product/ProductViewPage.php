<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\Product\ProductViewPage.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage\Product;

use Kanooh\Paddle\Pages\Element\Pane\Product\ProductContactPane;
use Kanooh\Paddle\Pages\Element\Pane\Product\ProductLeadImagePane;
use Kanooh\Paddle\Pages\Node\ViewPage\ViewPage;

/**
 * Class representing a Product page in the frontend view.
 *
 * @property ProductLeadImagePane $leadImagePane
 * @property ProductContactPane $contactPane
 */
class ProductViewPage extends ViewPage
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'leadImagePane':
                $element = $this->webdriver->byCssSelector('.pane-product-lead-image');
                return new ProductLeadImagePane($this->webdriver, $element->attribute('data-pane-uuid'));
            case 'contactPane':
                $element = $this->webdriver->byCssSelector('.pane-product-opening-hours');
                return new ProductContactPane($this->webdriver, $element->attribute('data-pane-uuid'));
        }
        return parent::__get($property);
    }
}
