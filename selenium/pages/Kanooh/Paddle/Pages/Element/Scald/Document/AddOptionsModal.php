<?php
/**
 * @file
 */

namespace Kanooh\Paddle\Pages\Element\Scald\Document;

use Kanooh\Paddle\Pages\Element\Scald\AddOptionsModalBase;

/**
 * Class AddOptionsModal
 * @package Kanooh\Paddle\Pages\Element\Scald\Document
 *
 * @property AddOptionsForm $form
 *   The options form.
 */
class AddOptionsModal extends AddOptionsModalBase
{
    public function __get($name)
    {
        switch ($name) {
            case 'form':
                return new AddOptionsForm($this->webdriver, $this->webdriver->byXPath($this->formXpath));
        }
    }
}
