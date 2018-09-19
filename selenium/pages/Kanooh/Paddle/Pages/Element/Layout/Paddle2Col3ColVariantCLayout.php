<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Layout\Paddle2Col3ColVariantCLayout.
 */

namespace Kanooh\Paddle\Pages\Element\Layout;

/**
 * A layout containing two rows.
 */
class Paddle2Col3ColVariantCLayout extends Layout
{

    const REGIONA = 'row_1_left';
    const REGIONB = 'row_1_middle';
    const REGIONC = 'row_1_right';
    const REGIOND = 'row_2_left';
    const REGIONE = 'row_2_right';

    /**
     * {@inheritdoc}
     */
    protected $info = array(
        'id' => 'paddle_2_cols_3_cols_c',
        'title' => 'Mu',
        'category' => 'Paddle Layouts',
        'regions' => array(
            'row_1_left' => 'A',
            'row_1_middle' => 'B',
            'row_1_right' => 'C',
            'row_2_left' => 'D',
            'row_2_right' => 'E',
        ),
    );
}
