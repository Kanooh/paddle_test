<?php

/**
 * @file
 * Contains
 *   \Kanooh\Paddle\Pages\Admin\Structure\TaxonomyManager\TaxonomyOverviewPage\OverviewPageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\Structure\TaxonomyManager\OverviewPage;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;

/**
 * The contextual toolbar for the generic administrative node view.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonCreateTerm
 *   The "Create term" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSave
 *   The "Save" button.
 */
class OverviewPageContextualToolbar extends ContextualToolbar
{

    /**
     * {@inheritdoc}
     */
    public function buttonInfo()
    {
        // Defines all the possible contextual buttons.
        $buttons = array(
            'CreateTerm' => array(
                'title' => 'Create Term',
            ),
            'Save' => array(
                'title' => 'Save',
                'href' => '',
            ),
        );

        return $buttons;
    }
}
