<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Wysiwyg\Table\TablePropertiesModalInfoForm.
 */

namespace Kanooh\Paddle\Pages\Element\Wysiwyg\Table;

use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Select;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class representing the Table Properties Modal Info Tab form.
 *
 * @property Checkbox $zebraStriping
 *   The form element representing the "Zebra Striping" checkbox.
 * @property Checkbox $hoverEffect
 *   The form element representing the "Hover Effect" checkbox.
 * @property Select $tableBordersStyle
 *   The form element representing the "Table borders" select.
 * @property Text $tableBordersSize
 *   The form element representing the "Borders size" text field.
 * @property Text $cellPadding
 *   The form element representing the "Cell padding" text field.
 * @property Text|null $cellSpacing
 *   The form element representing the "Cell spacing" text field.
 */
class TablePropertiesModalInfoForm extends Form
{
    // Name attribute of the tab's div.
    const TABNAME = 'info';

    // The content of the form element labels.
    const ZEBRASTRIPING = 'Zebra striping';

    // The label of the "Hover Effect" checkbox.
    const HOVEREFFECT = 'Hover effect';

    // The label of the "Table borders" select.
    const TABLEBORDERS = 'Table borders';

    // The label of the "Border size" text field.
    const BORDERSIZE = 'Border size';

    // The label of the "Cell padding" text field.
    const CELLPADDING = 'Cell padding';

    // The label of the "Cell spacing" text field.
    const CELLSPACING = 'Cell spacing';

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
            case 'zebraStriping':
                $id = $this->element->byXPath('.//label[.="' . self::ZEBRASTRIPING . '"]')->attribute('for');
                return new Checkbox($this->webdriver, $this->webdriver->byId($id));
            case 'hoverEffect':
                $id = $this->element->byXPath('.//label[.="' . self::HOVEREFFECT . '"]')->attribute('for');
                return new Checkbox($this->webdriver, $this->webdriver->byId($id));
            case 'tableBordersStyle':
                $id = $this->element->byXPath('.//label[.="' . self::TABLEBORDERS . '"]')->attribute('for');
                return new Select($this->webdriver, $this->webdriver->byId($id));
            case 'tableBordersSize':
                $id = $this->element->byXPath('.//label[.="' . self::BORDERSIZE . '"]')->attribute('for');
                return new Text($this->webdriver, $this->webdriver->byId($id));
            case 'cellPadding':
                $id = $this->element->byXPath('.//label[.="' . self::CELLPADDING . '"]')->attribute('for');
                return new Text($this->webdriver, $this->webdriver->byId($id));
            case 'cellSpacing':
                $id = $this->element->byXPath('.//label[.="' . self::CELLSPACING . '"]')->attribute('for');
                return new Text($this->webdriver, $this->webdriver->byId($id));
                break;
        }
        throw new FormFieldNotDefinedException($name);
    }
}
