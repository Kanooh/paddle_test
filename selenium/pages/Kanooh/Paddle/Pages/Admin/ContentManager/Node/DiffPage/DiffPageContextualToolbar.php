<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Node\DiffPage\DiffPageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Node\DiffPage;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;

/**
 * The contextual toolbar for the diff revision node page.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonRevisions
 *   The "Revisions" button.
 */
class DiffPageContextualToolbar extends ContextualToolbar
{

    /**
     * {@inheritdoc}
     */
    public function buttonInfo()
    {
        // Defines all the possible contextual buttons.
        $buttons = array(
            'Revisions' => array(
                'title' => 'Revisions',
            ),
        );

        return $buttons;
    }
}
