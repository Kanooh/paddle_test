<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Poll\PollView.
 */

namespace Kanooh\Paddle\Pages\Element\Poll;

use Kanooh\Paddle\Pages\Node\ViewPage\Poll\PollChart;
use Kanooh\Paddle\Pages\Node\ViewPage\Poll\PollViewForm;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class representing a poll rendering on the front-end.
 *
 * @property string $pollQuestion
 *   The text of the poll question.
 * @property PollViewForm $votingForm
 *   The voting form on the front-end form.
 * @property array $results
 *   The results of the voting displayed on the front-end form.
 * @property PollChart $chart
 *   The results displayed as chart.
 */
class PollView
{

    /**
     * The Selenium webdriver element representing the rendering.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    protected $element;

    /**
     * The Selenium webdriver.
     *
     * @var WebDriverTestCase
     */
    protected $webdriver;

    /**
     * Constructs a new PollView object.
     *
     * @param WebDriverTestCase $webdriver
     *   The Selenium webdriver.
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The Selenium webdriver element representing the form.
     */
    public function __construct(WebDriverTestCase $webdriver, \PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $this->webdriver = $webdriver;
        $this->element = $element;
    }

    /**
     * Magically provides all known elements of the poll rendering.
     *
     * @param string $property
     *   An element machine name.
     *
     * @return mixed
     *   The requested element.
     *
     * @throws \Exception
     */
    public function __get($property)
    {
        switch ($property) {
            case 'pollQuestion':
                $xpath = './/div[contains(@class, "field-name-field-paddle-poll-question")]' .
                    '//div[contains(@class, "field-item")]';
                return $this->element->byXPath($xpath)->text();
                break;
            case 'votingForm':
                $xpath = './/form[contains(@id, "poll-view-voting")]';
                return new PollViewForm($this->webdriver, $this->element->byXPath($xpath));
                break;
            case 'results':
                return $this->getResults();
                break;
            case 'chart':
                return new PollChart($this->webdriver);
                break;
        }

        throw new \Exception("Property with name $property not defined");
    }

    /**
     * Retrieves the results of the poll.
     *
     * @return array
     *   List of all the results and their values.
     */
    protected function getResults()
    {
        $results = array();

        $xpath = '//div[contains(@class, "poll")]/div[contains(@class, "text")]';
        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element[] $choices */
        $choices = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));

        $xpath = '//div[contains(@class, "poll")]/div[contains(@class, "percent")]';
        /** @var \PHPUnit_Extensions_Selenium2TestCase_Element[] $percents */
        $percents = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));

        foreach ($choices as $index => $choice) {
            preg_match('/(\d+)% \((\d+) vote/i', trim($percents[$index]->text()), $matches);
            $results[] = array(
              'choice_text' => $choice->text(),
              'percent' => $matches[1],
              'votes' => $matches[2],
            );
        }

        return $results;
    }
}
