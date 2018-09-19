<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage\ThemerOverviewPageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\Themer\ThemerOverviewPage;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;

/**
 * The contextual toolbar for the themer.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonCreateTheme
 *   The "Create theme" button.
 */
class ThemerOverviewPageContextualToolbar extends ContextualToolbar
{

    /**
     * {@inheritdoc}
     */
    public function buttonInfo()
    {
        // Defines all the possible contextual buttons.
        $buttons = array(
            'CreateTheme' => array(
                'title' => 'Create theme',
            ),
        );

        return $buttons;
    }
}
