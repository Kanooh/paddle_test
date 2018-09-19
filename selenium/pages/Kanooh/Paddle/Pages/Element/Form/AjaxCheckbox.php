<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Form\AjaxCheckbox.
 */

namespace Kanooh\Paddle\Pages\Element\Form;

use Kanooh\Paddle\Utilities\AjaxService;

/**
 * A form field representing a checkbox with ajax handlers attached.
 */
class AjaxCheckbox extends Checkbox
{
    /**
     * Checks the checkbox.
     *
     * This will check the current state of the checkbox, and will only click it
     * if needed and there is no ajax callback running.
     */
    public function check()
    {
        $ajax_service = new AjaxService($this->webdriver);
        if (!$this->element->selected() && !$ajax_service->isWaitingForAjaxCallback($this->element)) {
            $this->webdriver->moveto($this->element);
            $ajax_service->markAsWaitingForAjaxCallback($this->element);
            $this->element->click();
            $ajax_service->waitForAjaxCallback($this->element);
        }
    }

    /**
     * Unchecks the checkbox.
     *
     * This will check the current state of the checkbox, and will only click it
     * if needed and there is no ajax callback running.
     */
    public function uncheck()
    {
        $ajax_service = new AjaxService($this->webdriver);
        if ($this->element->selected() && !$ajax_service->isWaitingForAjaxCallback($this->element)) {
            $this->webdriver->moveto($this->element);
            $ajax_service->markAsWaitingForAjaxCallback($this->element);
            $this->element->click();
            $ajax_service->waitForAjaxCallback($this->element);
        }
    }
}
