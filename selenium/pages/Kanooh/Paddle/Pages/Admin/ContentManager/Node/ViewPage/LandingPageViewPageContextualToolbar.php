<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage\LandingPageViewPageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Node\ViewPage;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;
use Kanooh\Paddle\Pages\Element\Toolbar\DropdownButton;

/**
 * The contextual toolbar for the administrative node view of a landing page.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonPageLayout
 *   The "Page layout" button.
 * @property DropdownButton $dropdownButtonToEditor
 *   The "To editor" button.
 * @property DropdownButton $dropdownButtonToChiefEditor
 *   The "To chief editor" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonPageProperties
 *   The "Page properties" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonPreviewRevision
 *   The "Preview revision" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonOnlineVersion
 *   The "Online version" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonPublish
 *   The "Publish" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonOffline
 *   The "Offline" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonTranslations
 *   The "Translations" button.
 */
class LandingPageViewPageContextualToolbar extends ContextualToolbar
{

    /**
     * {@inheritdoc}
     */
    public function buttonInfo()
    {
        // Defines all the possible contextual buttons.
        $buttons = array(
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
            'ToChiefEditor' => array(
                'title' => 'To chief editor',
                'href' => '#',
            ),
            'PageProperties' => array(
                'title' => 'Page properties',
            ),
            'PreviewRevision' => array(
                'title' => 'Preview revision',
            ),
            'OnlineVersion' => array(
                'title' => 'Online version',
            ),
            'Publish' => array(
                'title' => 'Publish',
            ),
            'Offline' => array(
                'title' => 'Unpublish',
            ),
        );

        return $buttons;
    }
}
