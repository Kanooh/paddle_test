<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Display\PaddlePanelsDisplay.
 */

namespace Kanooh\Paddle\Pages\Element\Display;

/**
 * Base class for displays using the paddle_panels_renderer display renderer.
 *
 * @todo Currently Paddle Panels renderer is extending Panels IPE, but this is
 *   planned to become a standalone renderer. When this is done this class
 *   should be refactored and should no longer extend PanelsIPEDisplay.
 */
class PaddlePanelsDisplay extends PanelsIPEDisplay
{

    /**
     * Checks if the display is in editor status.
     *
     * This is always TRUE. This display is always in editor mode.
     *
     * @return bool
     *   Always TRUE.
     */
    protected function getEditorStatus()
    {
        return true;
    }
}
