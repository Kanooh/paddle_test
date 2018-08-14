<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\Product\ProductEditPage.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\Product;

use Kanooh\Paddle\Pages\Node\EditPage\EditPage;

/**
 * Page to edit a product.
 *
 * @property ProductEditForm $productEditForm
 */
class ProductEditPage extends EditPage
{
    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'productEditForm':
                return new ProductEditForm($this->webdriver, $this->webdriver->byId('paddle-product-node-form'));
        }
        return parent::__get($property);
    }
}
