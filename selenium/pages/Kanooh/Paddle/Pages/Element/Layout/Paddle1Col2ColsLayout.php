<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Layout\Paddle1Col2ColsLayout.
 */

namespace Kanooh\Paddle\Pages\Element\Layout;

/**
 * A layout containing two rows styles repeated 4 times.
 */
class Paddle1Col2ColsLayout extends Layout
{

    const REGIONA = 'row_1_left';
    const REGIONB = 'row_1_right';
    const REGIONC = 'row_2_full';
    const REGIOND = 'row_3_left';
    const REGIONE = 'row_3_right';
    const REGIONF = 'row_4_full';
    const REGIONG = 'row_5_left';
    const REGIONH = 'row_5_right';
    const REGIONI = 'row_6_full';
    const REGIONJ = 'row_7_left';
    const REGIONK = 'row_7_right';
    const REGIONL = 'row_8_full';

    /**
     * {@inheritdoc}
     */
    protected $info = array(
        'id' => 'paddle_1_col_2_cols',
        'title' => 'Omega',
        'category' => 'Paddle Layouts',
        'regions' => array(
            'row_1_left' => 'A',
            'row_1_right' => 'B',
            'row_2_full' => 'C',
            'row_3_left' => 'D',
            'row_3_right' => 'E',
            'row_4_full' => 'F',
            'row_5_left' => 'G',
            'row_5_right' => 'H',
            'row_6_full' => 'I',
            'row_7_left' => 'J',
            'row_7_right' => 'K',
            'row_8_full' => 'L',
        ),
    );
}
