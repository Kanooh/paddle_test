<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\Poll\PollChartLegend.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage\Poll;

use Kanooh\Paddle\Pages\Element\Element;

/**
 * Class representing the legend in a poll chart.
 *
 * @property string[] $labels
 *   An array of labels present in the legend.
 */
class PollChartLegend extends Element
{
    /**
     * {@inheritDoc}
     */
    protected $xpathSelector = '//div[contains(@class, "poll-chart-legend")]';

    /**
     * Magic getter for the element properties.
     *
     * @param string $property
     *   The name of the property.
     *
     * @return mixed
     *   The property value.
     *
     * @throws \Exception
     */
    public function __get($property)
    {
        switch ($property) {
            case 'labels':
                return $this->getLabels();
                break;
        }

        throw new \Exception("The property $property is undefined.");
    }

    /**
     * Retrieves all the labels shown in the legend.
     *
     * @return string[]
     *   The array of labels.
     */
    protected function getLabels()
    {
        $criteria = $this->webdriver->using('xpath')->value('.//div[contains(@class, "poll-chart-legend__label")]');
        $elements = $this->getWebdriverElement()->elements($criteria);

        $labels = array();
        foreach ($elements as $element) {
            /* @var \PHPUnit_Extensions_Selenium2TestCase_Element $element */
            $labels[] = $element->text();
        }

        return $labels;
    }
}
