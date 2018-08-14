<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Glossary\GlossaryDefinitionModal.
 */

namespace Kanooh\Paddle\Pages\Element\Glossary;

use Kanooh\Paddle\Pages\Element\Modal\Modal;
use Kanooh\Paddle\Pages\Element\Modal\ModalFormElementNotDefinedException;

/**
 * Class representing the add/edit modal for the glossary definition entities.
 *
 * @property GlossaryDefinitionForm $form
 */
class GlossaryDefinitionModal extends Modal
{
    /**
     * Magic getter for the modal properties.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'form':
                return new GlossaryDefinitionForm(
                    $this->webdriver,
                    $this->webdriver->byXPath('//form[contains(@id, "paddle-glossary-definition-form")]')
                );
        }
        throw new ModalFormElementNotDefinedException($name);
    }
}
