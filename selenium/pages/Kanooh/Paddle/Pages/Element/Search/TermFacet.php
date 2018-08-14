<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Search\TermFacet.
 */

namespace Kanooh\Paddle\Pages\Element\Search;

/**
 * Class that represents a term facet.
 */
class TermFacet extends Facet
{
    /**
     * Fetches the term id from the block classes.
     *
     * @return bool|int
     *   The term id, or false if no term found.
     */
    public function getTermId()
    {
        $classes = $this->element->attribute('class');
        preg_match('/pane-facetapi--tid-([0-9]+)/', $classes, $matches);
        return !empty($matches[1]) ? $matches[1] : false;
    }
}
