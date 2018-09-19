<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Quiz\View\QuizInfoScreen.
 */

namespace Kanooh\Paddle\Pages\Element\Quiz\View;

use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * Class QuizInfoScreen
 * @package Kanooh\Paddle\Pages\Element\Quiz\View
 *
 * @property Text $email
 *   E-mail address input field.
 * @property Text $name
 *   Name input field (if present).
 */
class QuizInfoScreen extends QuizScreen
{
    /**
     * XPath selector for the name input field.
     *
     * @var string
     */
    protected $nameXPathSelector = './/input[@type="text"][@name="name"]';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'email':
                $xpath = './/input[@type="text"][@name="email"]';
                $element = $this->element->byXPath($xpath);
                return new Text($this->webdriver, $element);
            case 'name':
                $element = $this->element->byXPath($this->nameXPathSelector);
                return new Text($this->webdriver, $element);
        }
        return parent::__get($property);
    }

    /**
     * Checks whether or not the "name" input field is present on the screen.
     *
     * @return boolean
     *   TRUE when present, FALSE if not.
     */
    public function isNameFieldPresent()
    {
        $criteria = $this->element->using('xpath')->value($this->nameXPathSelector);
        $elements = $this->element->elements($criteria);
        return !empty($elements);
    }
}
