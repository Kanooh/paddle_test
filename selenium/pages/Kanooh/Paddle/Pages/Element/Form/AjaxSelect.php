<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Form\AjaxSelect.
 */

namespace Kanooh\Paddle\Pages\Element\Form;

use Kanooh\Paddle\Utilities\AjaxService;

/**
 * A form field representing a select with ajax handlers attached.
 */
class AjaxSelect extends Select
{
    /**
     * Selects an option by label.
     *
     * @param string $label
     *   The label of the option to select.
     */
    public function selectOptionByLabel($label)
    {
        $this->waitForAjaxCallbackAfterCalling('getSelectedLabel', 'selectOptionByLabel', $label);
    }

    /**
     * Selects an option by value.
     *
     * @param string $value
     *   The value parameter of the option to select.
     */
    public function selectOptionByValue($value)
    {
        $this->waitForAjaxCallbackAfterCalling('getSelectedValue', 'selectOptionByValue', $value);
    }

    /**
     * Wait until AJAX is finished after selecting a new select option.
     *
     * @param string $getter
     *   The method to call on $this->select to retrieve $parameter.
     * @param string $setter
     *   The method to call on $this->select to set $parameter.
     * @param string $parameter
     *   The parameter to pass to the select method.
     */
    protected function waitForAjaxCallbackAfterCalling($getter, $setter, $parameter)
    {
        if ($this->$getter() == $parameter) {
            // Already selected.
            return;
        }

        $ajax_service = new AjaxService($this->webdriver);
        if (!$ajax_service->isWaitingForAjaxCallback($this->element)) {
            $this->webdriver->moveto($this->element);
            $ajax_service->markAsWaitingForAjaxCallback($this->element);
            $this->select->$setter($parameter);
            $ajax_service->waitForAjaxCallback($this->element);
        }
    }
}
