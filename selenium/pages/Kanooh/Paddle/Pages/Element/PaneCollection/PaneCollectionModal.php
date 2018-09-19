<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PaneCollection\PaneCollectionModal.
 */

namespace Kanooh\Paddle\Pages\Element\PaneCollection;

use Kanooh\Paddle\Pages\Element\Modal\Modal;
use Kanooh\Paddle\Pages\Element\Modal\ModalFormElementNotDefinedException;

/**
 * Class representing the add modal for the pane collection entities.
 *
 * @property PaneCollectionForm $form
 */
class PaneCollectionModal extends Modal
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'form':
                return new PaneCollectionForm(
                    $this->webdriver,
                    $this->webdriver->byXPath('//form[contains(@id, "paddle-pane-collection-form")]')
                );
        }
        throw new ModalFormElementNotDefinedException($name);
    }
}
