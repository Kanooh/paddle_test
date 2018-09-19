<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\SocialIdentities\SocialIdentityForm.
 */

namespace Kanooh\Paddle\Pages\Element\SocialIdentities;

use Jeremeamia\SuperClosure\SerializableClosure;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Form\UrlField;

/**
 * Class representing the Social Identity add/edit form.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $saveButton
 *   The form's save button.
 * @property Text $name
 *   The form's name field.
 * @property SocialIdentityTable $table
 *   The table containing the URL fields.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $addAnotherItemButton
 *   The "add another item" button.
 */
class SocialIdentityForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'saveButton':
                return $this->element->byXPath('.//input[contains(@id, "edit-save")]');
                break;
            case 'name':
                $element = $this->element->byXPath('.//input[@name="name"]');
                return new Text($this->webdriver, $element);
                break;
            case 'table':
                return new SocialIdentityTable($this->webdriver);
                break;
            case 'addAnotherItemButton':
                return $this->webdriver->byXPath('.//input[@name="field_social_identity_urls_add_more"]');
                break;
        }
    }

    /**
     * Creates a new empty table row with URL fields by clicking on the
     * "Add new item" button.
     *
     * @return SocialIdentityTableRow
     *   The new row created.
     */
    public function addNewUrlField()
    {
        $count_rows = $this->table->getNumberOfRows();
        $this->addAnotherItemButton->click();

        // We need to wai until the new row with the fields appears.
        $table = $this->table;
        $callable = new SerializableClosure(
            function () use ($count_rows, $table) {
                if ($table->getNumberOfRows() == ($count_rows + 1)) {
                    return true;
                }
            }
        );
        $this->webdriver->waitUntil($callable, $this->webdriver->getTimeout());

        return $table->getRowByPosition($table->getNumberOfRows() - 1);
    }
}
