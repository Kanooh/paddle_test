<?php
/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Breadcrumb\Breadcrumb.
 */

namespace Kanooh\Paddle\Pages\Element\Breadcrumb;

use Kanooh\Paddle\Pages\Element\Element;

class Breadcrumb extends Element
{
    /**
     * {@inheritdoc}
     */
    public $xpathSelector = '//div[@id="breadcrumb"]';

    /**
     * Returns the links in the breadcrumb.
     *
     * @return BreadcrumbLink[]
     *   Array of breadcrumb link objects.
     */
    public function getLinks()
    {
        $links = array();

        $xpath = $this->getXPathSelector() . '//ul/li';
        $criteria = $this->webdriver->using('xpath')->value($xpath);
        $elements = $this->webdriver->elements($criteria);

        foreach ($elements as $element) {
            $links[] = new BreadcrumbLink($element);
        }

        return $links;
    }
}
