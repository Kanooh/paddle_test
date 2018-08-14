<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Quiz\QuizReferenceField.
 */

namespace Kanooh\Paddle\Pages\Element\Quiz;

use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class QuizReferenceField
 * @package Kanooh\Paddle\Pages\Element\Quiz
 *
 * @property RadioButton[] $radioButtons
 *   A list of radio buttons, keyed by their quiz id.
 */
class QuizReferenceField
{
    /**
     * The Selenium webdriver.
     *
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * Field container div.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $container;

    /**
     * Constructs a new QuizReferenceField.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $container
     *   Field container div.
     */
    public function __construct(WebDriverTestCase $webdriver, $container)
    {
        $this->webdriver = $webdriver;
        $this->container = $container;
    }

    /**
     * Magic getter.
     */
    public function __get($property)
    {
        switch ($property) {
            case 'radioButtons':
                return $this->getRadioButtons();
                break;
        }

        throw new \Exception("The property $property is undefined.");
    }

    /**
     * Returns a list of radio buttons, keyed by their quiz id.
     *
     * @return RadioButton[]
     *   List of RadioButton elements, keyed by quiz ids.
     */
    protected function getRadioButtons()
    {
        $radios = array();

        $xpath = './/input[@type="radio"]';

        $criteria = $this->container->using('xpath')->value($xpath);
        $elements = $this->container->elements($criteria);

        /* @var \PHPUnit_Extensions_Selenium2TestCase_Element[] $elements */
        foreach ($elements as $element) {
            $qid = $element->attribute('value');
            $radios[$qid] = new RadioButton($this->webdriver, $element);
        }

        return $radios;
    }

    /**
     * Returns a radio button for a specific quiz based on its id.
     *
     * @param int $qid
     *   Id of the quiz for which to return the radio button.
     *
     * @return RadioButton
     *   Radio button for a specific quiz.
     */
    public function getRadioButton($qid)
    {
        $xpath = './/input[@type="radio"][@value="' . $qid . '"]';
        $element = $this->container->byXPath($xpath);
        return new RadioButton($this->webdriver, $element);
    }

    /**
     * Selects a specific quiz.
     *
     * @param int $qid
     *   Id of the quiz to select.
     */
    public function select($qid)
    {
        $radio = $this->getRadioButton($qid);
        $radio->select();
    }

    /**
     * Returns the selected quiz id.
     *
     * @return int
     *   Id of the selected quiz.
     */
    public function getSelectedQuizId()
    {
        $radios = $this->getRadioButtons();
        foreach ($radios as $radio) {
            if ($radio->isSelected()) {
                return $radio->getWebdriverElement()->attribute('value');
            }
        }
    }
}
