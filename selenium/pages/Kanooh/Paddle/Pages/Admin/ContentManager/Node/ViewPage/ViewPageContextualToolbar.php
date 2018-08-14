<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\ViewPageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;
use Kanooh\Paddle\Pages\Element\Toolbar\DropdownButton;

/**
 * The contextual toolbar for the generic administrative node view.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonPageProperties
 *   The "Page Properties" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonPageLayout
 *   The "Page layout" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonTranslations
 *   The "Translations" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonToEditor
 *   The "To editor" button.
 * @property DropdownButton $dropdownButtonToChiefEditor
 *   The "To chief editor" button.
 * @property DropdownButton $dropdownButtonToEditor
 *   The "To editor" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonPreviewRevision
 *   The "Preview revision" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonOnlineVersion
 *   The "Online version" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSchedule
 *   The "Schedule" ("Publish") button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonPublish
 *   The "Publish" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonDelete
 *   The "Delete" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonOffline
 *   The "Offline" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonArchive
 *   The "Archive" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonRestore
 *   The "Restore" button.
 */
class ViewPageContextualToolbar extends ContextualToolbar
{

    /**
     * {@inheritdoc}
     */
    public function buttonInfo()
    {
        // Defines all the possible contextual buttons.
        $buttons = array(
            'PageProperties' => array(
                'title' => 'Page properties',
            ),
            'PageLayout' => array(
                'title' => 'Page layout',
            ),
            'Translations' => array(
                'title' => 'Translations',
            ),
            'ToEditor' => array(
                'title' => 'To editor',
                'href' => '#',
            ),
            'ToAnyEditor' => array(
                'title' => 'Assign to any',
                'data-paddle-contextual-toolbar-click' => 'edit-moderate-to-check',
            ),
            'ToChiefEditor' => array(
                'title' => 'To chief editor',
                'href' => '#',
            ),
            'ToAnyChiefEditor' => array(
              'title' => 'Assign to any',
              'data-paddle-contextual-toolbar-click' => 'edit-moderate-needs-review',
            ),
            'PreviewRevision' => array(
                'title' => 'Preview revision',
            ),
            'OnlineVersion' => array(
                'title' => 'Online version',
            ),
            'Schedule' => array(
                'title' => 'Publish',
                'data-paddle-contextual-toolbar-click' => 'edit-moderate-scheduled',
            ),
            'Publish' => array(
                'title' => 'Publish',
                'data-paddle-contextual-toolbar-click' => 'edit-moderate-published',
            ),
            'Offline' => array(
                'title' => 'Unpublish',
            ),
            'Archive' => array(
                'title' => 'Archive',
            ),
            'Restore' => array(
                'title' => 'Restore',
            ),
            'Delete' => array(
                'title' => 'Delete',
            ),
        );

        return $buttons;
    }
}
