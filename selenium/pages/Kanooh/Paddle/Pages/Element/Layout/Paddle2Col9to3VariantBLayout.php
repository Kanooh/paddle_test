<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Layout\Paddle2Col9to3VariantBLayout.
 */

namespace Kanooh\Paddle\Pages\Element\Layout;

/**
 * A stacked two column layout with additional fixed top and right columns.
 */
class Paddle2Col9to3VariantBLayout extends Layout
{

    const REGIONTOPROW = 'top';
    const REGIONA = 'nested_top';
    const REGIONB = 'right';
    const REGIONC = 'nested_left';
    const REGIOND = 'nested_right';
    const REGIONE = 'nested_bottom';

    /**
     * {@inheritdoc}
     */
    protected $info = array(
        'id' => 'paddle_2_col_9_3_b',
        'title' => 'Epsilon',
        'category' => 'Paddle Layouts',
        'regions' => array(
            'nested_top' => 'A',
            'nested_left' => 'C',
            'nested_right' => 'D',
            'nested_bottom' => 'E',
            'right' => 'B',
        ),
    );
}
