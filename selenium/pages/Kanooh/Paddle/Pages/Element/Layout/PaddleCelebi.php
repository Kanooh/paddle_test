<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Layout\PaddleCelebi.
 */

namespace Kanooh\Paddle\Pages\Element\Layout;

/**
 * The 'Celebi' layout.
 */
class PaddleCelebi extends Layout
{

    const REGIONA = 'full_a';
    const REGIONB = 'nested_7_b';
    const REGIONC = 'nested_5_c';
    const REGIOND = 'bottom';
    const REGIONE = 'nested_6_e';
    const REGIONF = 'nested_6_f';
    const REGIONG = 'nested_4_g';
    const REGIONH = 'nested_4_h';
    const REGIONI = 'nested_4_i';
    const REGIONJ = 'right';

    /**
     * {@inheritdoc}
     */
    protected $info = array(
        'id' => 'paddle_celebi',
        'title' => 'Celebi',
        'category' => 'Paddle Layouts',
        'regions' => array(
            'full_a' => 'A',
            'nested_7_b' => 'B',
            'nested_5_c' => 'C',
            'bottom' => 'D',
            'nested_6_e' => 'E',
            'nested_6_f' => 'F',
            'nested_4_g' => 'G',
            'nested_4_h' => 'H',
            'nested_4_i' => 'I',
            'right' => 'J',
        ),
    );
}
