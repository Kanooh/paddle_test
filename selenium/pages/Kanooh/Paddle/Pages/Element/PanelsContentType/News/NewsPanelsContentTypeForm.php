<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\News\NewsPanelsContentTypeForm.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType\News;

use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;

/**
 * Class representing the news pane form.
 *
 * @property AutoCompletedText $newsAutocompleteField
 */
class NewsPanelsContentTypeForm extends Form
{

    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'newsAutocompleteField':
                return new AutoCompletedText($this->webdriver, $this->webdriver->byName('node'));
        }

        throw new FormFieldNotDefinedException($name);
    }
}
