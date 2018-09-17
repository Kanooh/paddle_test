<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\ContentRegionPanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Element;

/**
 * The 'Content region' Panels content type.
 */
class ContentRegionPanelsContentType extends PanelsContentType
{

    /**
     * {@inheritdoc}
     */
    const TYPE = 'content_region';

    /**
     * {@inheritdoc}
     */
    const TITLE = 'Region content';

    /**
     * {@inheritdoc}
     */
    const DESCRIPTION = 'Display content from a content region.';

    /**
     * The node type from which to take a region.
     *
     * The global type is 'all_pages'.
     *
     * @var string
     */
    public $type;

    /**
     * The region from which to take panes. Can be 'right' or 'bottom'.
     *
     * @var string
     */
    public $region;

    /**
     * {@inheritdoc}
     *
     * @todo Refactor to use the Form class.
     */
    public function fillInConfigurationForm(Element $element = null)
    {
        $xpath_selector = '//form[@id = "content-region-edit-form"]';

        $this->region = $this->region ?: $this->getRandomRegion();
        $xpath = $xpath_selector . '//input[@id = "edit-region-' . $this->region . '"]';
        $this->moveToAndClick($xpath);

        $this->type = $this->type ?: 'global';
        $xpath = $xpath_selector . '//div[@id = "edit-type"]//input[@value = "' . $this->type . '"]';
        $this->moveToAndClick($xpath);
    }

    /**
     * Returns a random region.
     *
     * @return string
     *   Either 'right' or 'bottom'.
     */
    public function getRandomRegion()
    {
        $regions = array('right', 'bottom');
        $key = array_rand($regions);
        return $regions[$key];
    }
}
