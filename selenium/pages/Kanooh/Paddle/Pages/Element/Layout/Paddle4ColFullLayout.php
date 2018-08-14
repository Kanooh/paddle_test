<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Layout\Paddle4ColFullLayout.
 */

namespace Kanooh\Paddle\Pages\Element\Layout;

/**
 * A four column layout.
 */
class Paddle4ColFullLayout extends Layout
{

    const REGIONA = 'column_one';
    const REGIONB = 'column_two';
    const REGIONC = 'column_three';
    const REGIOND = 'column_four';

    /**
     * {@inheritdoc}
     */
    protected $info = array(
        'id' => 'paddle_4_col_full',
        'title' => 'Iota',
        'category' => 'Paddle Layouts',
        'regions' => array(
            'column_one' => 'A',
            'column_two' => 'B',
            'column_three' => 'C',
            'column_four' => 'D',
        ),
    );
}
