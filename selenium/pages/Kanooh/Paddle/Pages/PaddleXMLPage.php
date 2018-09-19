<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\PaddleXMLPage.
 */

namespace Kanooh\Paddle\Pages;

use Kanooh\Paddle\Pages\PaddlePage;

/**
 * Represents a page which source is in XML format.
 */
abstract class PaddleXMLPage extends PaddlePage
{
    /**
     * {@inheritdoc}
     */
    protected $definingTag = '//xml';
}
