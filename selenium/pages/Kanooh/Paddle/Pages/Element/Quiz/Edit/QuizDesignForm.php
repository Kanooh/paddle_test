<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Quiz\Edit\QuizDesignForm.
 */

namespace Kanooh\Paddle\Pages\Element\Quiz\Edit;

use Kanooh\Paddle\Pages\Element\Form\FileField;
use Kanooh\Paddle\Pages\Element\Scald\ImageAtomField;

/**
 * Class QuizDesignForm
 *
 * @property ImageAtomField $startImage
 *   Start screen image field
 * @property FileField $cssFile
 *   Upload field for a custom CSS file.
 */
class QuizDesignForm extends QuizForm
{
    /**
     * Magic getter.
     */
    public function __get($name)
    {
        switch ($name) {
            case 'startImage':
                $xpath = './/div[contains(@class, "field-name-field-paddle-quiz-start-image")]';
                $element = $this->element->byXPath($xpath);
                return new ImageAtomField($this->webdriver, $element);
                break;
            case 'cssFile':
                $container_xpath = './/div[contains(@class, "form-item-field-paddle-quiz-css-und-0")]';
                $file_xpath = $container_xpath . '//input[@type="file"]';
                $upload_xpath = $container_xpath . '//input[@value="Upload"]';
                $remove_xpath = $container_xpath . '//input[@value="Remove"]';
                return new FileField($this->webdriver, $file_xpath, $upload_xpath, $remove_xpath);
                break;
        }
        return parent::__get($name);
    }
}
