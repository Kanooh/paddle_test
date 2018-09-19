<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Display\LandingPageDisplay.
 */

namespace Kanooh\Paddle\Pages\Element\Display;

/**
 * A display with configurable layout, used for landing pages.
 */
class LandingPageDisplay extends PaddlePanelsDisplay
{

    /**
     * {@inheritdoc}
     */
    protected $supportedLayouts = array(
        'paddle_1_col_2_cols' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle1Col2ColsLayout',
        'paddle_1_col_3_cols' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle1Col3ColsLayout',
        'paddle_2_col_3_9' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col3to9Layout',
        'paddle_2_col_4_8' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col4to8Layout',
        'paddle_2_col_6_6' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col6to6Layout',
        'paddle_2_col_8_4_a' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col8to4VariantALayout',
        'paddle_2_col_9_3' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col9to3Layout',
        'paddle_2_col_9_3_a' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col9to3VariantALayout',
        'paddle_2_col_9_3_b' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col9to3VariantBLayout',
        'paddle_2_col_9_3_c' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col9to3VariantCLayout',
        'paddle_2_col_9_3_d' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col9to3VariantDLayout',
        'paddle_2_col_9_3_bottom' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col9to3BottomLayout',
        'paddle_2_cols_3_cols' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col3ColLayout',
        'paddle_4_col' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle4ColLayout',
        'paddle_4_col_full' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle4ColFullLayout',
        'paddle_4_col_multiline' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle4ColMultilineLayout',
        'paddle_no_column' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle1ColLayout',
        'paddle_three_column' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle3ColLayout',
        'paddle_3_col_b' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle3ColVariantBLayout',
        'paddle_3_col_c' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle3ColVariantCLayout',
        'paddle_2_cols_3_cols_b' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col3ColVariantBLayout',
        'paddle_2_cols_3_cols_c' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col3ColVariantCLayout',
        'paddle_2_cols_3_cols_d' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col3ColVariantDLayout',
        'paddle_chi' => '\Kanooh\Paddle\Pages\Element\Layout\PaddleChi',
        'paddle_phi' => '\Kanooh\Paddle\Pages\Element\Layout\PaddlePhi',
        'paddle_celebi' => '\Kanooh\Paddle\Pages\Element\Layout\PaddleCelebi',
        'paddle_ampharos' => '\Kanooh\Paddle\Pages\Element\Layout\PaddleAmpharos',
    );
}
