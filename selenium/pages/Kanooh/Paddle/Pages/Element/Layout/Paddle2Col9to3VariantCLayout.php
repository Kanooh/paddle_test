<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Layout\Paddle2Col9to3VariantCLayout.
 */

namespace Kanooh\Paddle\Pages\Element\Layout;

/**
 * A stacked three column layout with a fixed right column.
 */
class Paddle2Col9to3VariantCLayout extends Layout
{

    const REGIONA = 'nested_top';
    const REGIONB = 'right';
    const REGIONC = 'nested_left';
    const REGIOND = 'nested_middle';
    const REGIONE = 'nested_right';
    const REGIONF = 'nested_bottom';

    /**
     * {@inheritdoc}
     */
    protected $info = array(
        'id' => 'paddle_2_col_9_3_c',
        'title' => 'Digamma',
        'category' => 'Paddle Layouts',
        'regions' => array(
            'nested_top' => 'A',
            'nested_left' => 'C',
            'nested_middle' => 'D',
            'nested_right' => 'E',
            'nested_bottom' => 'F',
            'right' => 'B',
        ),
    );
}
