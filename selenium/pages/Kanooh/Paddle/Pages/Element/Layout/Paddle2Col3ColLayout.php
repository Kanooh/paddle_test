<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Layout\Paddle2Col3ColLayout.
 */

namespace Kanooh\Paddle\Pages\Element\Layout;

/**
 * A layout with 2 columns in the top row and 3 columns in the bottom row.
 */
class Paddle2Col3ColLayout extends Layout
{

    const REGIONA = 'row_1_left';
    const REGIONB = 'row_1_right';
    const REGIONC = 'row_2_left';
    const REGIOND = 'row_2_middle';
    const REGIONE = 'row_2_right';

    /**
     * {@inheritdoc}
     */
    protected $info = array(
        'id' => 'paddle_2_cols_3_cols',
        'title' => 'Zeta',
        'category' => 'Paddle Layouts',
        'regions' => array(
            'row_1_left' => 'A',
            'row_1_right' => 'B',
            'row_2_left' => 'C',
            'row_2_middle' => 'D',
            'row_2_right' => 'E',
        ),
    );
}
