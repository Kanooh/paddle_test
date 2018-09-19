<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Node\RevisionsPage\RevisionsPageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Node\RevisionsPage;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;

/**
 * The contextual toolbar for the revisions node page.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonBack
 *   The "Back" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonCompare
 *   The "Compare" button.
 */
class RevisionsPageContextualToolbar extends ContextualToolbar
{

    /**
     * {@inheritdoc}
     */
    public function buttonInfo()
    {
        // Defines all the possible contextual buttons.
        $buttons = array(
            'Back' => array(
                'title' => 'Back',
            ),
            'Compare' => array(
                'title' => 'Compare',
            ),
        );

        return $buttons;
    }
}
