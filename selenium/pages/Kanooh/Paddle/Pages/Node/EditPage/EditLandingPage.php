<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\EditLandingPage.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage;

/**
 * Page to edit a landing page.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $showTitleCheckbox
 *   Checkbox to show/hide the page title.
 */
class EditLandingPage extends EditPage
{

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'showTitleCheckbox':
                return $this->webdriver->byName('field_show_title[und]');
        }
        return parent::__get($property);
    }
}
