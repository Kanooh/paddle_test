<?php
/**
 * @file
 */

namespace Kanooh\Paddle\Pages\Element\Scald\Document;

use Kanooh\Paddle\Pages\Element\Form\FileField;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Scald\AddOptionsFormBase;
use Kanooh\Paddle\Pages\Element\Form\Checkbox;

/**
 * Class AddOptionsForm
 * @package Kanooh\Paddle\Pages\Element\Scald\Document
 *
 * @property Text $title
 * @property FileField $document
 */
class AddOptionsForm extends AddOptionsFormBase
{
    public function __get($name)
    {
        switch ($name) {
            case 'title':
                $element = $this->element->byXPath('.//input[@name="atom0[title]"]');

                return new Text($this->webdriver, $element);
            case 'document':
                return new FileField(
                    $this->webdriver,
                    '//input[@name="files[atom0_paddle_scald_file_und_0]"]',
                    '//input[@name="atom0_paddle_scald_file_und_0_upload_button"]',
                    '//input[@name="atom0_paddle_scald_file_und_0_remove_button"]'
                );
            case 'checkboxDeleteDate':
                return new Checkbox($this->webdriver, $this->webdriver->byId('edit-atom0-field-scald-set-removal-date-und'));
                break;
            case 'removeDate':
                return new Text($this->webdriver, $this->element->byName('atom0[field_paddle_scald_atom_end_date][und][0][value][date]'));
                break;
            case 'removeTime':
                return new Text($this->webdriver, $this->element->byName('atom0[field_paddle_scald_atom_end_date][und][0][value][time]'));
                break;
        }

        return parent::__get($name);
    }
}
