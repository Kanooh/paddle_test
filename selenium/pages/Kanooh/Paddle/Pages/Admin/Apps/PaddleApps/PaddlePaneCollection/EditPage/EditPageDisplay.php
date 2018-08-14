<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddlePaneCollection\EditPage\EditPageDisplay.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddlePaneCollection\EditPage;

use Kanooh\Paddle\Pages\Element\Display\PaddlePanelsDisplay;

/**
 * A display with configurable layout, used for Pane Collection entities.
 */
class EditPageDisplay extends PaddlePanelsDisplay
{
    /**
     * {@inheritdoc}
     */
    protected $supportedLayouts = array(
        'paddle_no_column' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle1ColLayout',
    );
}
