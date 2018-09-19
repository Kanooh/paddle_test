<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Display\ContentRegionDisplayPage.
 */

namespace Kanooh\Paddle\Pages\Element\Display;

use Kanooh\Paddle\Pages\Admin\ContentManager\PanelsContentPage\PanelsContentPageContextualToolbar;

/**
 * Base class for all Pages that use the content region display renderer.
 *
 * @property ContentRegionDisplay $display
 *   The Panels display.
 * @property PanelsContentPageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 */
abstract class ContentRegionDisplayPage extends PaddlePanelsDisplayPage
{

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'display':
                return new ContentRegionDisplay($this->webdriver);
            case 'contextualToolbar':
                return new PanelsContentPageContextualToolbar($this->webdriver);
        }
        return parent::__get($property);
    }
}
