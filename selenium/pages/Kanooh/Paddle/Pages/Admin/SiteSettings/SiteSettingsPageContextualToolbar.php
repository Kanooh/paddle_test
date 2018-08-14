<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\SiteSettings\SiteSettingsPageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\SiteSettings;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;

/**
 * The contextual toolbar for the Site Settings Page.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSave
 *   The "Save" button.
 */
class SiteSettingsPageContextualToolbar extends ContextualToolbar
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
            ),
        );

        return $buttons;
    }
}
