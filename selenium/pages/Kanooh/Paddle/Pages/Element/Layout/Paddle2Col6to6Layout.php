<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Layout\Paddle2Col6to6Layout.
 */

namespace Kanooh\Paddle\Pages\Element\Layout;

/**
 * A layout containing two columns of equal width.
 */
class Paddle2Col6to6Layout extends Layout
{

    const REGIONA = 'left';
    const REGIONB = 'right';

    /**
     * {@inheritdoc}
     */
    protected $info = array(
        'id' => 'paddle_2_col_6_6',
        'title' => 'Beta',
        'category' => 'Paddle Layouts',
        'regions' => array(
            'left' => 'A',
            'right' => 'B',
        ),
    );
}
