<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Apps\Product.
 */

namespace Kanooh\Paddle\Apps;

/**
 * The Product app.
 */
class Product implements AppInterface
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'paddle-product';
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return 'paddle_product';
    }

    /**
     * {@inheritdoc}
     */
    public function isConfigurable()
    {
        return false;
    }
}
