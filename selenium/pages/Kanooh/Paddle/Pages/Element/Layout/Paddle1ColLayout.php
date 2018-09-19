<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Layout\Paddle1ColLayout.
 */

namespace Kanooh\Paddle\Pages\Element\Layout;

/**
 * A simple layout containing a single column.
 */
class Paddle1ColLayout extends Layout
{

    const REGIONA = 'middle';

    /**
     * {@inheritdoc}
     */
    protected $info = array(
        'id' => 'paddle_no_column',
        'title' => 'Eta',
        'category' => 'Paddle Layouts',
        'regions' => array(
            'middle' => 'A',
        ),
    );
}
