<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Modal\EditPaddleStyleModal.
 */

namespace Kanooh\Paddle\Pages\Element\Modal;

/**
 * The modal that allows to edit the Paddle Style of a pane.
 *
 * @property EditPaddleStyleForm $form
 *   The main form in the modal.
 */
class EditPaddleStyleModal extends Modal
{
    /**
     * {@inheritdoc}
     */
    protected $submitButtonXPathSelector = '//input[@id="edit-submit"]';

    /**
     * Magically provides all known links as properties.
     *
     * @param string $name
     *   A link machine name of the property we are looking for.
     *
     * @return mixed
     *   The matching element object.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'form':
                $xpath = '//form[contains(@id, "paddle-panels-renderer-paddle-style-plugins-form")]';
                return new EditPaddleStyleForm($this->webdriver, $this->webdriver->byXPath($xpath));
        }

        trigger_error('Undefined property: ' . __CLASS__ . '::$' . $name, E_USER_NOTICE);

        return false;
    }
}
