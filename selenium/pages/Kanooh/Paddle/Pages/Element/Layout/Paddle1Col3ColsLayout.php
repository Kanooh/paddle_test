<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Layout\Paddle1Col3ColsLayout.
 */

namespace Kanooh\Paddle\Pages\Element\Layout;

/**
 * A layout containing two rows styles repeated 4 times.
 */
class Paddle1Col3ColsLayout extends Layout
{

    const REGIONA = 'row_1_full';
    const REGIONB = 'row_2_left';
    const REGIONC = 'row_2_center';
    const REGIOND = 'row_2_right';
    const REGIONE = 'row_3_full';
    const REGIONF = 'row_4_left';
    const REGIONG = 'row_4_center';
    const REGIONH = 'row_4_right';
    const REGIONI = 'row_5_full';
    const REGIONJ = 'row_6_left';
    const REGIONK = 'row_6_center';
    const REGIONL = 'row_6_right';
    const REGIONM = 'row_7_full';
    const REGIONN = 'row_8_left';
    const REGIONO = 'row_8_center';
    const REGIONP = 'row_8_right';

    /**
     * {@inheritdoc}
     */
    protected $info = array(
        'id' => 'paddle_1_col_3_cols',
        'title' => 'Psi',
        'category' => 'Paddle Layouts',
        'regions' => array(
            'row_1_full' => 'A',
            'row_2_left' => 'B',
            'row_2_center' => 'C',
            'row_2_right' => 'D',
            'row_3_full' => 'E',
            'row_4_left' => 'F',
            'row_4_center' => 'G',
            'row_4_right' => 'H',
            'row_5_full' => 'I',
            'row_6_left' => 'J',
            'row_6_center' => 'K',
            'row_6_right' => 'L',
            'row_7_full' => 'M',
            'row_8_left' => 'N',
            'row_8_center' => 'O',
            'row_8_right' => 'P',
        ),
    );
}
