<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddlePaneCollection\EditPage\EditPageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddlePaneCollection\EditPage;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;

/**
 * The contextual toolbar for the Pane Collection entity edit Page.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonBack
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSave
 */
class EditPageContextualToolbar extends ContextualToolbar
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
            'Save' => array(
                'title' => 'Save',
            ),
        );

        return $buttons;
    }
}
