<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\PaddleMegaDropdown\EditPage\EditPageDisplay.
 */

namespace Kanooh\Paddle\Pages\Admin\PaddleMegaDropdown\EditPage;

use Kanooh\Paddle\Pages\Element\Display\PaddlePanelsDisplay;

/**
 * A display with configurable layout, used for Mega Dropdown entities.
 */
class EditPageDisplay extends PaddlePanelsDisplay
{
    /**
     * {@inheritdoc}
     */
    protected $supportedLayouts = array(
        'paddle_2_col_6_6' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col6to6Layout',
        'paddle_three_column' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle3ColLayout',
        'paddle_4_col_full' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle4ColFullLayout',
    );
}
