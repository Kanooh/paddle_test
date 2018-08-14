<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleRichFooter\ConfigurePage\ConfigurePageDisplay.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleRichFooter\ConfigurePage;

use Kanooh\Paddle\Pages\Element\Display\PaddlePanelsDisplay;

/**
 * A display with configurable layout, used for the Rich Footer.
 */
class ConfigurePageDisplay extends PaddlePanelsDisplay
{
    /**
     * {@inheritdoc}
     */
    protected $supportedLayouts = array(
        //'paddle_2_col_6_6' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col6to6Layout',
        //'paddle_three_column' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle3ColLayout',
        'paddle_4_col_full' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle4ColFullLayout',
    );
}
