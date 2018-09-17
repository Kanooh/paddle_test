<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\UiTDatabankPane.
 */

namespace Kanooh\Paddle\Pages\Element\Pane;

use Kanooh\Paddle\Pages\Element\Cultuurnet\UiTDatabankEvent;
use Kanooh\Paddle\Pages\Element\Cultuurnet\UiTDatabankSearchForm;
use Kanooh\Paddle\Pages\Element\PanelsContentType\UiTDatabankPanelsContentType;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for a Panels pane with Ctools content type 'UiTDatabank Pane'.
 *
 * @property UiTDatabankEvent $event
 * @property UiTDatabankSearchForm
 */
class UiTDatabankPane extends Pane
{

    /**
     * @var UiTDatabankPanelsContentType
     */
    public $contentType;

    /**
     * Constructs a UiTDatabankPane.
     *
     * @param WebDriverTestCase $webdriver
     *   The webdriver object.
     * @param string $uuid
     *   The uuid of the pane.
     */
    public function __construct(WebDriverTestCase $webdriver, $uuid)
    {
        parent::__construct($webdriver, $uuid);
        $this->contentType = new UiTDatabankPanelsContentType($this->webdriver);
    }

    /**
     * Magic getter.
     */
    public function __get($property)
    {
        $selection_type = $this->contentType->selection_type;
        switch ($property) {
            case 'event':
                if ($selection_type == "spotlight") {
                    $xpath = $this->xpathSelector . '//div[contains(@class, "paddle-cultuurnet-spotlight-event")]';
                    $element = $this->webdriver->byXPath($xpath);
                    $event = new UiTDatabankEvent($this->webdriver, $element);

                    return $event;
                }
                break;
            case 'searchForm':
                if ($selection_type == "search") {
                    $xpath = $this->xpathSelector . '//form[contains(@id, "culturefeed-agenda-search-block-form")]';
                    $element = $this->webdriver->byXPath($xpath);
                    $search_form = new UiTDatabankSearchForm($this->webdriver, $element);

                    return $search_form;
                }
                break;
        }
        throw new \Exception("The property with the name $property is not defined for selection type $selection_type.");
    }
}
