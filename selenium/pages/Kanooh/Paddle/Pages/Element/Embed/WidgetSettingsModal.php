<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Embed\WidgetSettingsModal.
 */

namespace Kanooh\Paddle\Pages\Element\Embed;

use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Class WidgetSettingsModal
 *
 * @property WidgetSettingsForm $form
 */
class WidgetSettingsModal extends Modal
{
    public function __get($name)
    {
        switch ($name) {
            case 'form':
                return new WidgetSettingsForm($this->webdriver, $this->webdriver->byXPath('//form[contains(@id, "paddle-embed-widget-settings-form")]'));
        }
    }
}
