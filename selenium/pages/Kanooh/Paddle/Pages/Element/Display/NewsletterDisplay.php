<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Display\NewsletterDisplay.
 */

namespace Kanooh\Paddle\Pages\Element\Display;

/**
 * A display with configurable layout, used for newsletters.
 */
class NewsletterDisplay extends PaddlePanelsDisplay
{
    /**
     * {@inheritdoc}
     */
    protected $supportedLayouts = array(
      'paddle_2_col_3_9' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col3to9Layout',
      'paddle_2_col_6_6' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col6to6Layout',
      'paddle_2_col_9_3' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col9to3Layout',
      'paddle_no_column' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle1ColLayout',
    );
}
