<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Display\LandingPageDisplayPage.
 */

namespace Kanooh\Paddle\Pages\Element\Display;

/**
 * Base class for all Pages that use the landing page display renderer.
 *
 * @property LandingPageDisplay $display
 *   The Panels display.
 */
abstract class LandingPageDisplayPage extends PaddlePanelsDisplayPage
{

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'display':
                return new LandingPageDisplay($this->webdriver);
        }
        return parent::__get($property);
    }
}
