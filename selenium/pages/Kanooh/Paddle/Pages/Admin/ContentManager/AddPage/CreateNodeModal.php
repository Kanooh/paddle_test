<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\ContentManager\AddPage\CreateNodeModal.
 */

namespace Kanooh\Paddle\Pages\Admin\ContentManager\AddPage;

use Kanooh\Paddle\Pages\Element\Form\Select;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Modal\Modal;

/**
 * Class representing the modal dialog for creating new nodes.
 *
 * @package Kanooh\Paddle\Pages\Admin\ContentManager\AddPage
 *
 * @property Text $title
 *   The title textfield.
 * @property Select $language
 *   The language dropdown.
 */
class CreateNodeModal extends Modal
{

    protected $submitButtonXPathSelector = '//form[contains(@id, "paddle-content-manager-node-add-form")]//input[@class="form-submit"]';

    /**
     * Magic getter.
     *
     * @param string $property
     *   The name of the property which we want.
     */
    public function __get($property)
    {
        switch ($property) {
            case 'title':
                $xpath = '//form[@id="paddle-content-manager-node-add-form"]//input[@name = "title"]';
                return new Text($this->webdriver, $this->webdriver->byXPath($xpath));
            case 'language':
                $xpath = '//form[@id="paddle-content-manager-node-add-form"]//select[@name = "language"]';
                return new Select($this->webdriver, $this->webdriver->byXPath($xpath));
        }
        throw new \Exception("The property $property is undefined.");
    }
}
