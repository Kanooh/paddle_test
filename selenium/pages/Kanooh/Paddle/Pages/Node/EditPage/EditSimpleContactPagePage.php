<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\EditSimpleContactPagePage.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage;

/**
 * Page to edit a simple contact page.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $labelOptionsLink
 *   The link to open/close the "Label Options" fieldset.
 */
class EditSimpleContactPagePage extends EditPage
{

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'labelOptionsLink':
                $label_options_xpath = '//fieldset[@id="edit-field-paddle-contact-form-und-0-label-options"]' .
                    '//a[contains(@class, "fieldset-title")]';
                return $this->webdriver->byXpath($label_options_xpath);
        }
        return parent::__get($property);
    }
}
