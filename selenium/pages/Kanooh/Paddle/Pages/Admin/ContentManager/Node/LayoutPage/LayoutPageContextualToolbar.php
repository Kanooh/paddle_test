<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage\LayoutPageContextualToolbar.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\Node\LayoutPage;

use Kanooh\Paddle\Pages\Element\Toolbar\ContextualToolbar;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * The contextual toolbar for the Panels layout page.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonBack
 *   The "Cancel" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonSave
 *   The "Save" button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $buttonChangeLayout
 *   The "Change layout" button.
 */
class LayoutPageContextualToolbar extends ContextualToolbar
{

    /**
     * Constructs a LayoutPageContextualToolbar object.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     */
    public function __construct(WebDriverTestCase $webdriver)
    {
        parent::__construct($webdriver);
    }

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
            'ChangeLayout' => array(
              'title' => 'Change layout',
            ),
        );

        return $buttons;
    }
}
