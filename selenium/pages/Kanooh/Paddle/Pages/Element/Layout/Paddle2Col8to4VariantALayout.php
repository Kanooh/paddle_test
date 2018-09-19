<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Layout\Paddle2Col8to4VariantALayout.
 */

namespace Kanooh\Paddle\Pages\Element\Layout;

/**
 * A two column layout with a fixed right column.
 */
class Paddle2Col8to4VariantALayout extends Layout
{

    const REGIONA = 'row_1_full';
    const REGIONB = 'row_2_left';
    const REGIONC = 'row_2_right';
    const REGIOND = 'row_3_full';
    const REGIONE = 'row_4_left';
    const REGIONF = 'row_4_right';
    const REGIONG = 'row_5_full';
    const REGIONH = 'row_6_left';
    const REGIONI = 'row_6_right';
    const REGIONJ = 'row_7_full';
    const REGIONK = 'row_8_left';
    const REGIONL = 'row_8_right';
    const REGIONM = 'right';

    /**
     * {@inheritdoc}
     */
    protected $info = array(
        'id' => 'paddle_2_col_8_4_a',
        'title' => 'Rho',
        'category' => 'Paddle Layouts',
        'regions' => array(
            'row_1_full' => 'A',
            'row_2_left' => 'B',
            'row_2_right' => 'C',
            'row_3_full' => 'D',
            'row_4_left' => 'E',
            'row_4_right' => 'F',
            'row_5_full' => 'G',
            'row_6_left' => 'H',
            'row_6_right' => 'I',
            'row_7_full' => 'J',
            'row_8_left' => 'K',
            'row_8_right' => 'L',
            'right' => 'M',
        ),
    );
}
