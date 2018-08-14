<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Layout\Paddle2Col9to3VariantALayout.
 */

namespace Kanooh\Paddle\Pages\Element\Layout;

/**
 * Layout containing a stacked two column layout with a fixed right column.
 */
class Paddle2Col9to3VariantALayout extends Layout
{

    const REGIONA = 'nested_top';
    const REGIONB = 'right';
    const REGIONC = 'nested_left';
    const REGIOND = 'nested_right';
    const REGIONE = 'nested_bottom';

    /**
     * {@inheritdoc}
     */
    protected $info = array(
        'id' => 'paddle_2_col_9_3_a',
        'title' => 'Delta',
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
