<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\ViewPage\Poll\PollChart.
 */

namespace Kanooh\Paddle\Pages\Node\ViewPage\Poll;

use Kanooh\Paddle\Pages\Element\Element;

/**
 * Class representing the chart for the poll results.
 *
 * @property PollChartLegend $legend
 *   The legend for this chart.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $table
 *   The table that contains the data for accessibility purposes.
 */
class PollChart extends Element
{
    /**
     * {@inheritDoc}
     */
    protected $xpathSelector = '//div[contains(@class, "charts-google")]';

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
            case 'legend':
                return new PollChartLegend($this->webdriver);
                break;
            case 'table':
                return $this->getWebdriverElement()->byXPath('.//table');
                break;
        }

        throw new \Exception("The property $property is undefined.");
    }

    /**
     * Extracts the chart type from the configuration property.
     *
     * @return string
     *   The Google Chart type.
     */
    public function getType()
    {
        $data = json_decode($this->getWebdriverElement()->attribute('data-chart'));

        return $data->visualization;
    }
}
