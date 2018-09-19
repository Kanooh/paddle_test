<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Wysiwyg\Table\TablePropertiesModalAdvancedForm.
 */

namespace Kanooh\Paddle\Pages\Element\Wysiwyg\Table;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class representing the Table Properties Modal Advanced Tab form.
 *
 * @property Text $stylesheetClasses
 *   The form element representing the Stylesheet Classes text field.
 */
class TablePropertiesModalAdvancedForm extends Form
{
    // Name attribute of the tab's div.
    const TABNAME = 'advanced';

    // The content of the form element labels.
    const STYLESHEETCLASSESLABEL = 'Stylesheet Classes';

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        // CKEditor uses dynamically generated numeric IDs for all its elements
        // so we have to use XPath trickery to find our elements. These will
        // probably need be be tweaked if the CKEditor configuration changes.
        //
        // The strategy that is used to find the elements is to limit the lookup
        // per tab and target the form element labels on their label text. The
        // labels contain a "for" property which contains the ID of the form
        // element.
        switch ($name) {
            case 'stylesheetClasses':
                $id = $this->element->byXPath('.//label[.="' . self::STYLESHEETCLASSESLABEL . '"]')->attribute('for');
                return new Text($this->webdriver, $this->webdriver->byId($id));
        }
        throw new FormFieldNotDefinedException($name);
    }
}
