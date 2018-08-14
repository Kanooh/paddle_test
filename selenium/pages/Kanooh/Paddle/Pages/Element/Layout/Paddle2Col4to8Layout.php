<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Layout\Paddle2Col4to8Layout.
 */

namespace Kanooh\Paddle\Pages\Element\Layout;

/**
 * A two column layout with a fixed left column.
 */
class Paddle2Col4to8Layout extends Layout
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
        'id' => 'paddle_2_col_4_8',
        'title' => 'Tau',
        'category' => 'Paddle Layouts',
        'regions' => array(
          'left' => 'A',
          'row_1_full' => 'B',
          'row_2_left' => 'C',
          'row_2_right' => 'D',
          'row_3_full' => 'E',
          'row_4_left' => 'F',
          'row_4_right' => 'G',
          'row_5_full' => 'H',
          'row_6_left' => 'I',
          'row_6_right' => 'J',
          'row_7_full' => 'K',
          'row_8_left' => 'L',
          'row_8_right' => 'M',
        ),
    );
}
