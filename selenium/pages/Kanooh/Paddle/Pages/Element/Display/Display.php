<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Display\Display.
 */

namespace Kanooh\Paddle\Pages\Element\Display;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\Layout\Layout;
use Kanooh\Paddle\Pages\Element\Region\RegionNotPresentException;
use Kanooh\Paddle\Pages\Element\Region\Region;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Base class for page elements that represent a Panels display.
 */
abstract class Display extends Element
{
    /**
     * {@inheritdoc}
     */
    protected $xpathSelector = '//div[contains(@class, "panel-display")]';

    /**
     * The layout that is used by the current display.
     *
     * @var Layout
     */
    public $layout;

    /**
     * The layouts that are supported by the display.
     *
     * @var array
     *   An associative array of Layout classes that are supported by this
     *   display, keyed by layout machine name.
     */
    protected $supportedLayouts = array(
        'paddle_1_col_2_cols' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle1Col2ColsLayout',
        'paddle_1_col_3_cols' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle1Col3ColsLayout',
        'paddle_2_col_3_9' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col3to9Layout',
        'paddle_2_col_3_9_flexible' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col3to9FlexibleLayout',
        'paddle_2_col_4_8' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col4to8Layout',
        'paddle_2_col_6_6' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col6to6Layout',
        'paddle_2_col_8_4_a' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col8to4VariantALayout',
        'paddle_2_col_9_3' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col9to3Layout',
        'paddle_2_col_9_3_a' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col9to3VariantALayout',
        'paddle_2_col_9_3_b' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col9to3VariantBLayout',
        'paddle_2_col_9_3_bottom' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col9to3BottomLayout',
        'paddle_2_col_9_3_c' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col9to3VariantCLayout',
        'paddle_2_col_9_3_d' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col9to3VariantDLayout',
        'paddle_2_cols_3_cols' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col3ColLayout',
        'paddle_3_col_b' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle3ColVariantBLayout',
        'paddle_3_col_c' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle3ColVariantCLayout',
        'paddle_2_cols_3_cols_b' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col3ColVariantBLayout',
        'paddle_2_cols_3_cols_c' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col3ColVariantCLayout',
        'paddle_2_cols_3_cols_d' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle2Col3ColVariantDLayout',
        'paddle_4_col' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle4ColLayout',
        'paddle_4_col_full' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle4ColFullLayout',
        'paddle_4_col_multiline' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle4ColMultilineLayout',
        'paddle_no_column' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle1ColLayout',
        'paddle_three_column' => '\Kanooh\Paddle\Pages\Element\Layout\Paddle3ColLayout',
        'paddle_celebi' => '\Kanooh\Paddle\Pages\Element\Layout\PaddleCelebi',
        'paddle_chi' => '\Kanooh\Paddle\Pages\Element\Layout\PaddleChi',
        'paddle_phi' => '\Kanooh\Paddle\Pages\Element\Layout\PaddlePhi',
        'paddle_ampharos' => '\Kanooh\Paddle\Pages\Element\Layout\PaddleAmpharos',
    );

    /**
     * The regions which are held by this region.
     *
     * @var Region[]
     */
    protected $regions;

    /**
     * Whether or not the display is in editor mode.
     *
     * @var bool $isEditor
     */
    protected $isEditor;

    /**
     * {@inheritdoc}
     *
     * When the display is instantiated it will
     * - Probe the page that is currently displayed in the browser to see which
     *   layout is in use.
     * - Verify that the layout is supported by the display.
     * - Populate the regions list.
     *
     * @param string $xpath_selector
     *   An optional XPath selector that uniquely identifies the display on the
     *   page. This should be used if multiple displays are present on the page.
     *   If omitted the default selector will be used.
     */
    public function __construct(WebDriverTestCase $webdriver, $xpath_selector = '')
    {
        parent::__construct($webdriver);

        // Replace the default XPath selector if a more specific one has been
        // provided.
        $this->xpathSelector = $xpath_selector ?: $this->xpathSelector;

        $this->layout = $this->getCurrentLayout();
        $this->isEditor = $this->getEditorStatus();
        $this->regions = $this->getCurrentRegions();
    }

    /**
     * Returns the XPath selector for the region with the given id.
     *
     * @param string $id
     *   The machine name of the region.
     *
     * @return string
     *   The XPath selector for the region.
     */
    abstract protected function getRegionXPathSelector($id);

    /**
     * Returns a region from the Panels display.
     *
     * @param string $id
     *   The machine name of the region.
     *
     * @return Region
     *   The requested region.
     *
     * @throws RegionNotPresentException
     *   Thrown when the requested region is not present.
     */
    public function region($id)
    {
        if (!isset($this->regions[$id])) {
            throw new RegionNotPresentException($id);
        }
        return $this->regions[$id];
    }

    /**
     * Returns the Layout with the given name.
     *
     * @param string $id
     *   The machine name of the layout. Defaults to the current layout.
     *
     * @return Layout
     *   The requested Layout object.
     *
     * @throws LayoutNotDefinedException
     *   Thrown if the given layout class does not exist.
     * @throws LayoutNotSupportedException
     *   Thrown if the layout is not supported by the current display.
     */
    public function getCurrentLayout($id = null)
    {
        // Default to the current layout.
        $id = $id ?: $this->getCurrentLayoutId();

        if (!isset($this->supportedLayouts[$id])) {
            throw new LayoutNotSupportedException($id);
        }

        if (!class_exists($this->supportedLayouts[$id])) {
            throw new LayoutNotDefinedException($this->supportedLayouts[$id]);
        }

        return new $this->supportedLayouts[$id]($this->webdriver);
    }

    /**
     * Returns the id of the layout that is used by the current display.
     *
     * This is retrieved from the current browser page.
     *
     * @return string
     *   The id of the current layout.
     */
    public function getCurrentLayoutId()
    {
        $xpath = $this->xpathSelector . '/div';
        $element = $this->webdriver->element($this->webdriver->using('xpath')->value($xpath));
        $classes = explode(' ', $element->attribute('class'));
        foreach ($classes as $class) {
            if (strpos($class, 'paddle-layout-') === 0) {
                return substr($class, 14);
            }
        }

        return false;
    }

    /**
     * Instantiates new regions from the currently active layout.
     *
     * @return Region[]
     *   An array of regions that are present in the current display.
     */
    protected function getCurrentRegions()
    {
        $regions = array();
        foreach ($this->layout->getRegions() as $id => $name) {
            $regions[$id] = new Region($this->webdriver, $id, $name, $this->getRegionXPathSelector($id));
        }
        return $regions;
    }

    /**
     * Checks if the display is in editor mode.
     *
     * This is retrieved from the current browser page. The standard Panels
     * display is only in editor mode when accessed from within the Panels UI.
     *
     * @todo This code is as of yet untested. Verify if this works once we add
     *   a test that actually goes through the Panels UI to edit a display.
     *
     * @return bool
     *   TRUE if the display is in editor status. false otherwise.
     */
    protected function getEditorStatus()
    {
        $element = $this->webdriver->byXPath('//div[@id="panels-dnd-main"]');
        return !empty($element);
    }

    /**
     * Checks the presence of an array of regions on a panelized page.
     *
     * @param array $names
     *   Array containing the machine names of the expected regions.
     *   If omitted, will check for the presence of all defined regions.
     *
     * @deprecated This method seems to be broken because it uses an undefined
     *   method layoutInfo(). Consider removing it, or fix it if needed.
     */
    public function checkRegions(array $names = array())
    {
        // Default to all available regions.
        $info = $this->layoutInfo();
        $names = $names ?: array_keys($info[$this->layout]['regions']);

        foreach ($names as $name) {
            // Access the region. If the region is not present this will throw
            // an exception.
            $this->region($name);
        }
    }

    /**
     * Returns the available regions.
     *
     * @return Region[]
     *   An array of regions that are present in the current display.
     */
    public function getRegions()
    {
        return $this->regions;
    }

    /**
     * Gets the supported layouts for this display.
     *
     * @return array
     *   The supported layouts keyed by layout id.
     */
    public function getSupportedLayouts()
    {
        return $this->supportedLayouts;
    }

    /**
     * Returns a random region.
     *
     * @return Region
     *   One of the regions that are present in the current display.
     */
    public function getRandomRegion()
    {
        $key = array_rand($this->regions);
        return $this->regions[$key];
    }

    /**
     * Checks whether the display is in editor mode.
     *
     * @throws DisplayNotInEditorModeException
     *   Thrown when the display is not in editor mode.
     */
    public function checkInEditorMode()
    {
        if (!$this->getEditorStatus()) {
            throw new DisplayNotInEditorModeException();
        }
    }

    /**
     * Checks whether the display is in view mode.
     *
     * @throws DisplayNotInViewModeException
     *   Thrown when the display is not in view mode.
     */
    public function checkInViewMode()
    {
        if ($this->getEditorStatus()) {
            throw new DisplayNotInViewModeException();
        }
    }
}
