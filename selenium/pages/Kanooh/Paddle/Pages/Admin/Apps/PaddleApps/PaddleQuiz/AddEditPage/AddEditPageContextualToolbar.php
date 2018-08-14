<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleQuiz\AddEditPage\AddEditPageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleQuiz\AddEditPage;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;

/**
 * The contextual toolbar for the add/edit page of the Quiz app.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonCancel
 *   The "Cancel" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSave
 *   The "Save" button.
 */
class AddEditPageContextualToolbar extends ContextualToolbar
{
    /**
     * {@inheritdoc}
     */
    public function buttonInfo()
    {
        return array(
            'Cancel' => array(
                'title' => 'Cancel',
            ),
            'Save' => array(
                'title' => 'Save',
            ),
        );
    }
}
