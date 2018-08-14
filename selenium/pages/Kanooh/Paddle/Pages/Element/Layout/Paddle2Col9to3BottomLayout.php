<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Layout\Paddle2Col9to3BottomLayout.
 */

namespace Kanooh\Paddle\Pages\Element\Layout;

/**
 * Layout containing two columns with an additional bottom row.
 */
class Paddle2Col9to3BottomLayout extends Layout
{

    const REGIONBOTTOM = 'bottom';
    const REGIONLEFT = 'left';
    const REGIONRIGHT = 'right';

    /**
     * {@inheritdoc}
     */
    protected $info = array(
        'id' => 'paddle_2_col_9_3_bottom',
        'title' => 'Node',
        'category' => 'Paddle Layouts',
        'regions' => array(
            'right' => 'Right',
            'left' => 'Left',
            'bottom' => 'Bottom',
        ),
    );
}
