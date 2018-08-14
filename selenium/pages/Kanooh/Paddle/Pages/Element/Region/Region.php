<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Region\Region.
 */

namespace Kanooh\Paddle\Pages\Element\Region;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\Modal\AddPaneModal;
use Kanooh\Paddle\Pages\Element\Pane\Pane;
use Kanooh\Paddle\Pages\Element\PanelsContentType\PanelsContentType;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for regions.
 *
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $styleButton
 */
class Region extends Element
{

    /**
     * The region machine name.
     *
     * @var string
     */
    protected $id;

    /**
     * The human readable name of the region.
     *
     * @var string
     */
    protected $name;

    /**
     * The panes that are present in this region.
     *
     * @var Pane[]
     */
    protected $panes;

    /**
     * The XPath selector that identifies all panes within this Region.
     *
     * @var string
     */
    protected $paneCommonXPathSelector = '//div[contains(@class, "panel-pane")]';

    /**
     * The pane UUID Xpath selector.
     *
     * Used to discover individual panes within a region.
     *
     * @var string
     */
    protected $paneUuidXPathSelector = '//*[@data-pane-uuid="%uuid"]';

    /**
     * The 'Add pane' button.
     *
     * @var \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public $buttonAddPane;

    /**
     * Whether the node the regions belongs to is locked.
     *
     * @var bool
     */
    public $locked;

    /**
     * Constructs a Region.
     *
     * @todo This currently assumes that the Region is in editor mode, and adds
     *   some buttons. This will need to be refactored once we want to use this
     *   in the frontend.
     *
     * @param WebDriverTestCase $webdriver
     *   The web driver.
     * @param string $id
     *   The machine name of the region.
     * @param string $name
     *   The human readable name of the region.
     * @param string $xpath_selector
     *   The XPath selector for this region.
     * @param bool $locked
     *   Whether the region is locked.
     */
    public function __construct(WebDriverTestCase $webdriver, $id, $name, $xpath_selector, $locked = false)
    {
        parent::__construct($webdriver);

        $this->id = $id;
        $this->name = $name;
        $this->xpathSelector = $xpath_selector;
        $this->panes = $this->getCurrentPanes();
        $this->locked = $locked;
        if (!$locked) {
            $this->buttonAddPane = $this->getAddPaneButton();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'styleButton':
                return !$this->locked ? $this->webdriver->byXPath($this->xpathSelector . '//a[@title="Region style"]') : false;
                break;
        }

        throw new \Exception("The property $property is undefined.");
    }

    /**
     * Returns the machine name of the region.
     *
     * @return string
     *   The region machine name.
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Returns the human readable name of the region.
     *
     * @return string
     *   The human readable name of the region.
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Returns the panes that are contained in this region.
     *
     * @return Pane[]
     *   An array of pane elements.
     */
    public function getPanes()
    {
        return $this->panes;
    }

    /**
     * Returns the panes that are currently present in this region.
     *
     * This is retrieved from the browser.
     *
     * @return Pane[]
     *   An array of pane elements.
     */
    protected function getCurrentPanes()
    {
        $panes = array();

        $xpath = $this->getPaneCommonXPathSelector();
        if ($elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath))) {
            foreach ($elements as $element) {
                $pane = new Pane($this->webdriver, $this->getPaneUuid($element), $this->getPaneXPathSelector($element));
                $panes[$pane->getUuid()] = $pane;
            }
        }

        return $panes;
    }

    /**
     * Retrieves the full XPath selector for all the panes in the region.
     *
     * @return string
     *   The XPath selector.
     */
    protected function getPaneCommonXPathSelector()
    {
        return $this->xpathSelector . $this->paneCommonXPathSelector;
    }

    /**
     * Returns the "Add pane" button from a region.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function getAddPaneButton()
    {
        $xpath = $this->xpathSelector . '//a[@title="Add new pane"]';
        $buttons = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
        return !empty($buttons) ? reset($buttons) : null;
    }

    /**
     * Adds a pane to the region.
     *
     * @param PanelsContentType $pane_type
     *   The type of pane to create.
     * @param callable $callback
     *   Custom callback to execute when the configuration modal is open. (If
     *   not provided, the deprecated fillInConfigurationForm method will be
     *   used.)
     *
     * @return Pane
     *   The pane we just added.
     */
    public function addPane(PanelsContentType $pane_type, $callback = null)
    {
        $panes_before = $this->getPanes();

        // Open the Add Pane dialog.
        $this->buttonAddPane->click();
        $modal = new AddPaneModal($this->webdriver);
        $modal->waitUntilOpened();

        // Select the pane type in the modal dialog.
        $modal->selectContentType($pane_type);

        if (!empty($callback)) {
            call_user_func($callback, $modal);
        } else {
            $pane_type->fillInConfigurationForm($modal);
        }

        $modal->submit();
        $modal->waitUntilClosed();

        $this->refreshPaneList();
        $panes_after = $this->getPanes();

        $pane_new = current(array_diff_key($panes_after, $panes_before));
        return $pane_new;
    }

    /**
     * Update the internal $panes variable.
     */
    public function refreshPaneList()
    {
        $this->panes = $this->getCurrentPanes();
    }

    /**
     * Get the UUID from the webdriver element representing a pane.
     *
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The Selenium webdriver element representing the pane.
     *
     * @return string
     *   The UUID.
     *
     * @throws RegionPaneUuidNotFoundException
     *   Thrown when the UUID could not be found.
     */
    public function getPaneUuid(\PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        try {
            return $element->attribute('data-pane-uuid');
        } catch (\Exception $e) {
            throw new RegionPaneUuidNotFoundException();
        }
    }

    /**
     * Get the XPath selector for the pane in the given webdriver element.
     *
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $element
     *   The Selenium webdriver element representing the pane.
     *
     * @return string
     *   The XPath selector.
     */
    public function getPaneXPathSelector(\PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        $pane_xpath_selector = str_replace('%uuid', $this->getPaneUuid($element), $this->paneUuidXPathSelector);
        return $this->xpathSelector . $pane_xpath_selector;
    }

    /**
     * Checks if a pane is present in the current region.
     *
     * @param Pane $pane
     *   The Pane that we are looking for.
     *
     * @return bool
     *   TRUE if the pane is present in the region, FALSE otherwise.
     */
    public function checkPanePresent(Pane $pane)
    {
        return in_array($pane->getUuid(), $this->panes);
    }
}
