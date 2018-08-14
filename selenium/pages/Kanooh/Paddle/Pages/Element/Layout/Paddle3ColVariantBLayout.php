<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Layout\Paddle3ColVariantBLayout.
 */

namespace Kanooh\Paddle\Pages\Element\Layout;

/**
 * A 3 column layout with one top full width row
 */
class Paddle3ColVariantBLayout extends Layout
{

    const REGIONA = 'top';
    const REGIONB = 'first_left';
    const REGIONC = 'second_middle';
    const REGIOND = 'third_right';

    /**
     * {@inheritdoc}
     */
    protected $info = array(
        'id' => 'paddle_3_col_b',
        'title' => 'Omicron',
        'category' => 'Paddle Layouts',
        'regions' => array(
            'top' => 'A',
            'first_left' => 'B',
            'second_middle' => 'C',
            'third_right' => 'D',
        ),
    );
}
