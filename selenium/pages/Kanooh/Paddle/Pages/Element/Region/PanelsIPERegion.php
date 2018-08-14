<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Region\PanelsIPERegion.
 */

namespace Kanooh\Paddle\Pages\Element\Region;

/**
 * Base class for regions.
 */
class PanelsIPERegion extends Region
{

    /**
     * {@inheritdoc}
     */
    protected $paneCommonXPathSelector = '//div[contains(@class, "panels-ipe-portlet-wrapper")]';

    /**
     * The pane HTML id prefix.
     *
     * @var string
     */
    protected $idHtmlIdPrefix = 'panels-ipe-paneid-';

    /**
     * Returns the drop zone element of this region.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     *   Drop zone element.
     */
    public function dropZone()
    {
        return $this->getWebdriverElement()->byClassName('panels-ipe-sort-container');
    }
}
