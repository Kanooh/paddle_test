<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Layout\Paddle2Col9to3Layout.
 */

namespace Kanooh\Paddle\Pages\Element\Layout;

/**
 * Layout containing a wide left column and a narrow right column.
 */
class Paddle2Col9to3Layout extends Layout
{

    const REGIONA = 'left';
    const REGIONB = 'right';

    /**
     * {@inheritdoc}
     */
    protected $info = array(
        'id' => 'paddle_2_col_9_3',
        'title' => 'Gamma',
        'category' => 'Paddle Layouts',
        'regions' => array(
            'left' => 'A',
            'right' => 'B',
        ),
    );
}
