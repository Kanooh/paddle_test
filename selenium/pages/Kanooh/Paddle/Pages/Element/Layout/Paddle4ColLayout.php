<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Layout\Paddle4ColLayout.
 */

namespace Kanooh\Paddle\Pages\Element\Layout;

/**
 * A four column layout.
 */
class Paddle4ColLayout extends Layout
{

    const REGIONA = 'nested_top';
    const REGIONB = 'second_column';
    const REGIONC = 'third_column';
    const REGIOND = 'nested_left';
    const REGIONE = 'nested_right';

    /**
     * {@inheritdoc}
     */
    protected $info = array(
        'id' => 'paddle_4_col',
        'title' => 'Sigma',
        'category' => 'Paddle Layouts',
        'regions' => array(
            'nested_top' => 'A',
            'nested_left' => 'D',
            'nested_right' => 'E',
            'second_column' => 'B',
            'third_column' => 'C',
        ),
    );
}
