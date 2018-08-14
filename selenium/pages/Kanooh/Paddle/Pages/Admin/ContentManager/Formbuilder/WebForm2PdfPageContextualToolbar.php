<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder\WebForm2PdfPageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Formbuilder;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;

/**
 * The contextual toolbar for the WebForm2Pdf formbuilder page.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonBack
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSave
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonReset
 */
class WebForm2PdfPageContextualToolbar extends ContextualToolbar
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
