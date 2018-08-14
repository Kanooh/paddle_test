<?php

/**
 * @file
 * Contains
 *   \Kanooh\Paddle\Pages\Admin\Structure\ContentRegionPage\ContentRegionPageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\Structure\ContentRegionPage;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;

/**
 * The contextual toolbar for the generic administrative node view.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSave
 *   The "Save" button.
 */
class ContentRegionPageContextualToolbar extends ContextualToolbar
{

    /**
     * {@inheritdoc}
     */
    public function buttonInfo()
    {
        // Defines all the possible contextual buttons.
        $buttons = array(
            'Save' => array(
                'title' => 'Save',
                'href' => '',
            ),
        );

        return $buttons;
    }
}
