<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Display\PanelsDisplayPage.
 */

namespace Kanooh\Paddle\Pages\Element\Display;

use Kanooh\Paddle\Pages\PaddlePage;

/**
 * Extends PaddlePage with a display from the Panels module.
 *
 * @property PanelsDisplay $display
 *   The Panels display.
 */
abstract class PanelsDisplayPage extends PaddlePage
{

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'display':
                return new PanelsDisplay($this->webdriver);
        }
        return parent::__get($property);
    }
}
