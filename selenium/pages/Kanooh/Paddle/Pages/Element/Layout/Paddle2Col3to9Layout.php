<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Layout\Paddle2Col3to9Layout.
 */

namespace Kanooh\Paddle\Pages\Element\Layout;

/**
 * A layout containing a narrow left column and a wide right column.
 */
class Paddle2Col3to9Layout extends Layout
{

    const REGIONA = 'left';
    const REGIONB = 'right';

    /**
     * {@inheritdoc}
     */
    protected $info = array(
        'id' => 'paddle_2_col_3_9',
        'title' => 'Alfa',
        'category' => 'Paddle Layouts',
        'regions' => array(
            'left' => 'A',
            'right' => 'B',
        ),
    );
}
