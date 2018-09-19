<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Layout\Paddle3ColVariantCLayout.
 */

namespace Kanooh\Paddle\Pages\Element\Layout;

/**
 * A 3 column layout with one top full width row.
 */
class Paddle3ColVariantCLayout extends Layout
{

    const REGIONA = 'top';
    const REGIONB = '1_a';
    const REGIONC = '2_a';
    const REGIOND = '3_b';
    const REGIONE = '4_b';
    const REGIONF = '5_b';

    /**
     * {@inheritdoc}
     */
    protected $info = array(
        'id' => 'paddle_3_col_c',
        'title' => 'Nu',
        'category' => 'Paddle Layouts',
        'regions' => array(
            'top' => 'A',
            '1_a' => 'B',
            '2_a' => 'C',
            '3_b' => 'D',
            '4_b' => 'E',
            '5_b' => 'F',
        ),
    );
}
