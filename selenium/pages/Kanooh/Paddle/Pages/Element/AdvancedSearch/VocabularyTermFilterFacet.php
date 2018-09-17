<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\AdvancedSearch\VocabularyTermFilterFacet.
 */

namespace Kanooh\Paddle\Pages\Element\AdvancedSearch;

use Kanooh\Paddle\Pages\Element\Search\TermFacet;

/**
 * Class representing a vocabulary term filter facet.
 */
class VocabularyTermFilterFacet extends TermFacet
{
    /**
     * {@inheritdoc}
     */
    public function getTermId()
    {
        $classes = $this->element->attribute('class');
        preg_match('/pane-vocabulary-term-filter--tid-([0-9]+)/', $classes, $matches);
        return !empty($matches[1]) ? $matches[1] : false;
    }
}
