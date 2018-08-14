<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\CodexFlanders\CodexFlandersPanelsContentTypeForm.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\CodexFlanders;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Utilities\DrupalApi\DrupalAjaxApi;

/**
 * Class representing the Codex Flanders pane form.
 *
 * @property Text $name
 * @property Text $url
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $addAnotherCodex
 */
class CodexFlandersPanelsContentTypeForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'name':
                return new Text($this->webdriver, $this->element->byName('codices[1][name]'));
                break;
            case 'url':
                return new Text($this->webdriver, $this->element->byName('codices[1][url]'));
                break;
            case 'addAnotherCodex':
                return $this->element->byId('edit-add-codex');
                break;
        }

        throw new FormFieldNotDefinedException($name);
    }

    /**
     * Removes a codex entry.
     *
     * @param string $name
     *   The name of the codex remove button.
     */
    public function removeCodexByName($name)
    {
        $element = $this->webdriver->byName($name);
        $element->click();
        $drupalAjaxApi = new DrupalAjaxApi($this->webdriver);
        $drupalAjaxApi->waitUntilElementFinishedAjaxing($element);
    }
}
