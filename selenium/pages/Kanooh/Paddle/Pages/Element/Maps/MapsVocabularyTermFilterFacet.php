<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\Maps\MapsVocabularyTermFilterFacet.
 */

namespace Kanooh\Paddle\Pages\Element\Maps;

use Kanooh\Paddle\Pages\Element\Search\TermFacet;

/**
 * Class representing a vocabulary term filter facet.
 */
class MapsVocabularyTermFilterFacet extends TermFacet
{
    /**
     * {@inheritdoc}
     */
    public function getTermId()
    {
        $classes = $this->element->attribute('class');
        preg_match('/pane-map-vocabulary-term-filter--tid-([0-9]+)/', $classes, $matches);
        return !empty($matches[1]) ? $matches[1] : false;
    }
}
