<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Display\PaddlePanelsDisplayPage.
 */

namespace Kanooh\Paddle\Pages\Element\Display;

/**
 * Extends PanelsDisplayPage with functionality tied to the Paddle Renderer.
 *
 * @property PaddlePanelsDisplay $display
 *   The Paddle Panels display.
 */
abstract class PaddlePanelsDisplayPage extends PanelsIPEDisplayPage
{

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'display':
                return new PaddlePanelsDisplay($this->webdriver);
        }
        return parent::__get($property);
    }
}
