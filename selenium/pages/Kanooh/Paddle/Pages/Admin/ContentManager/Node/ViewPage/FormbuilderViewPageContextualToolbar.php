<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\FormbuilderViewPageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage;

use Kanooh\Paddle\Pages\Element\Toolbar\DropdownButton;

/**
 * The contextual toolbar for the Fomrbuilder administrative node view.
 *
 * @property DropdownButton $dropdownButtonForm
 *   The "Form" button that opens the relative dropdown.
 */
class FormbuilderViewPageContextualToolbar extends ViewPageContextualToolbar
{

    /**
     * {@inheritdoc}
     */
    public function buttonInfo()
    {
        // Defines all the possible contextual buttons.
        $buttons = array(
            'Form' => array(
                'title' => 'Form',
            ),
            'Submissions' => array(
                'title' => 'Submissions',
            ),
            'Download' => array(
                'title' => 'Download',
            ),
        );

        $buttons += parent::buttonInfo();

        return $buttons;
    }
}
