<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Layout\Paddle4ColMultilineLayout.
 */

namespace Kanooh\Paddle\Pages\Element\Layout;

/**
 * A four column multiline layout.
 */
class Paddle4ColMultilineLayout extends Layout
{

    const REGIONA = 'nested_6_a';
    const REGIONB = 'nested_3_a';
    const REGIONC = 'nested_3_b';
    const REGIOND = 'nested_3_c';
    const REGIONE = 'nested_3_d';
    const REGIONF = 'nested_3_e';
    const REGIONG = 'nested_3_f';
    const REGIONH = 'nested_6_b';
    const REGIONI = 'nested_3_g';
    const REGIONJ = 'nested_3_h';
    const REGIONK = 'full_bottom';

    /**
     * {@inheritdoc}
     */
    protected $info = array(
        'id' => 'paddle_4_col',
        'title' => 'Sigma',
        'category' => 'Paddle Layouts',
        'regions' => array(
            'nested_6_a' => 'A',
            'nested_3_a' => 'B',
            'nested_3_b' => 'C',
            'nested_3_c' => 'D',
            'nested_3_d' => 'E',
            'nested_3_e' => 'F',
            'nested_3_f' => 'G',
            'nested_6_b' => 'H',
            'nested_3_g' => 'I',
            'nested_3_h' => 'J',
            'full_bottom' => 'K',
        ),
    );
}
