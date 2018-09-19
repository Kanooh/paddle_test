<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Layout\Paddle3ColLayout.
 */

namespace Kanooh\Paddle\Pages\Element\Layout;

/**
 * A three column layout.
 */
class Paddle3ColLayout extends Layout
{

    const REGIONA = 'left';
    const REGIONB = 'middle';
    const REGIONC = 'right';

    /**
     * {@inheritdoc}
     */
    protected $info = array(
        'id' => 'paddle_three_column',
        'title' => 'Theta',
        'category' => 'Paddle Layouts',
        'regions' => array(
            'left' => 'A',
            'middle' => 'B',
            'right' => 'C',
        ),
    );
}
