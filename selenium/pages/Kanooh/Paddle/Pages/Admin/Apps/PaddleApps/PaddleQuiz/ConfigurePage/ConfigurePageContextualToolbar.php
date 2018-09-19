<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleQuiz\ConfigurePage\ConfigurePageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleQuiz\ConfigurePage;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;

/**
 * The contextual toolbar for the configure page of the Quiz app.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonBack
 *   The "Back" (to the Paddle Store) button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSave
 *   The save button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonCreate
 *   The "Create quiz" button.
 */
class ConfigurePageContextualToolbar extends ContextualToolbar
{
    /**
     * {@inheritdoc}
     */
    public function buttonInfo()
    {
        return array(
            'Back' => array(
                'title' => 'Back',
            ),
            'Save' => array(
                'title' => 'Save',
            ),
            'Create' => array(
                'title' => 'Create quiz',
            ),
        );
    }
}
