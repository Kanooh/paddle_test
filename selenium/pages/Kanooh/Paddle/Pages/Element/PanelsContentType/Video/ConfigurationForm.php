<?php
/**
 * @file
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\Video;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Scald\AtomField;

/**
 * Class ConfigurationForm
 * @package Kanooh\Paddle\Pages\Element\PanelsContentType\Video
 *
 * @property AtomField $video
 */
class ConfigurationForm extends Form
{
    public function __get($name)
    {
        switch ($name) {
            case 'video':
                return new AtomField($this->webdriver, $this->element->byXPath('.//div/input[@name="video"]/..'));
        }
    }
}
