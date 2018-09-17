<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Layout\Paddle2Col9to3VariantDLayout.
 */

namespace Kanooh\Paddle\Pages\Element\Layout;

/**
 * A three column layout with a fixed right column.
 */
class Paddle2Col9to3VariantDLayout extends Layout
{

    const REGIONA = 'nested_top';
    const REGIONB = 'right';
    const REGIONC = 'nested_left';
    const REGIOND = 'nested_right';
    const REGIONE = 'bottom';

    /**
     * {@inheritdoc}
     */
    protected $info = array(
        'id' => 'paddle_2_col_9_3_d',
        'title' => 'Lamda',
        'category' => 'Paddle Layouts',
        'regions' => array(
            'nested_top' => 'A',
            'nested_left' => 'C',
            'nested_right' => 'D',
            'bottom' => 'E',
            'right' => 'B',
        ),
    );
}
