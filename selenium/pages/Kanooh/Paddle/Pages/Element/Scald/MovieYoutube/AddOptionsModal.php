<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Scald\MovieYoutube\AddOptionsModal.
 */

namespace Kanooh\Paddle\Pages\Element\Scald\MovieYoutube;

use Kanooh\Paddle\Pages\Element\Scald\AddOptionsModalBase;

/**
 * Modal to change the options of a Youtube video.
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
