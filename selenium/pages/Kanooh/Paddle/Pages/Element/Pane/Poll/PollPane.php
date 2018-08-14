<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\Poll\PollPane.
 */

namespace Kanooh\Paddle\Pages\Element\Pane\Poll;

use Kanooh\Paddle\Pages\Element\Pane\Pane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\PollPanelsContentType;
use Kanooh\WebDriver\WebDriverTestCase;
use Kanooh\Paddle\Pages\Element\Poll\PollView;

/**
 * Base class for a Panels pane with Ctools content type 'Poll'.
 *
 * @property PollView $pollView
 *   The rendering of a poll on the front-end.
 */
class PollPane extends Pane
{

    /**
     * The object for the pane content type.
     *
     * @var PollPanelsContentType
     */
    public $contentType;

    /**
     * Constructs a PollPane pane.
     *
     * @param WebDriverTestCase $webdriver
     *   The webdriver object.
     * @param string $uuid
     *   The uuid of the pane.
     * @param string $pane_xpath_selector
     *   More general xpath selector for the pane.
     */
    public function __construct(WebDriverTestCase $webdriver, $uuid, $pane_xpath_selector)
    {
        parent::__construct($webdriver, $uuid, $pane_xpath_selector);

        $this->contentType = new PollPanelsContentType($this->webdriver);
    }

    /**
     * Magically provides all known elements of the pane.
     *
     * @param string $name
     *   An element machine name.
     *
     * @return mixed
     *   The requested pane element.
     *
     * @throws \Exception
     */
    public function __get($name)
    {
        switch ($name) {
            case 'pollView':
                $element = $this->webdriver->byCssSelector('div.pane-content div.node-poll');
                return new PollView($this->webdriver, $element);
                break;
        }

        throw new \Exception("Property with name $name not defined");
    }
}
