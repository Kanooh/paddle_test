<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Pane\MenuStructurePane.
 */

namespace Kanooh\Paddle\Pages\Element\Pane;

use Kanooh\Paddle\Pages\Element\PanelsContentType\MenuStructurePanelsContentType;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Class for a Panels pane with Ctools content type 'Menu structure'.
 */
class MenuStructurePane extends Pane
{

    /**
     * The object for the pane content type.
     *
     * @var MenuStructurePanelsContentType
     */
    public $contentType;

    /**
     * Constructs a MenuStructurePane.
     *
     * @param WebDriverTestCase $webdriver
     *   The webdriver object.
     * @param string $uuid
     *   The uuid of the pane.
     * @param string $pane_xpath_selector
     *   More general xpath selector for the pane.
     */
    public function __construct(WebDriverTestCase $webdriver, $uuid, $pane_xpath_selector = '')
    {
        parent::__construct($webdriver, $uuid, $pane_xpath_selector);

        $this->contentType = new MenuStructurePanelsContentType($this->webdriver);
    }
}
