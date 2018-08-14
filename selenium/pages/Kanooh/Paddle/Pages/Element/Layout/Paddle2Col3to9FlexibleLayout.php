<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Layout\Paddle2Col3to9FlexibleLayout.
 */

namespace Kanooh\Paddle\Pages\Element\Layout;

/**
 * A layout containing a narrow left column and a wide right column.
 *
 * When the left column is empty, it gets hidden and the right column takes
 * full width.
 */
class Paddle2Col3to9FlexibleLayout extends Layout
{

    const REGIONA = 'left';
    const REGIONB = 'right';

    /**
     * {@inheritdoc}
     */
    protected $info = array(
        'id' => 'paddle_2_col_3_9_flexible',
        'title' => 'Alfa Flexible',
        'category' => 'Paddle Layouts',
        'regions' => array(
            'left' => 'A',
            'right' => 'B',
        ),
    );
}
