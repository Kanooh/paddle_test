<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Utilities\TaxonomyService.
 */

namespace Kanooh\Paddle\Utilities;

class TaxonomyService
{
    /**
     * The vid of the Tags vocabulary.
     */
    const TAGS_VOCABULARY_ID = 1;

    /**
     * The vid of the General Tags vocabulary.
     */
    const GENERAL_TAGS_VOCABULARY_ID = 2;

    /**
     * Creates a taxonomy term through Drupal.
     *
     * @param int $vid
     *   The id of the vocabulary in which to create the term.
     * @param string $title
     *   The title of the term.
     * @param integer $parent
     *   The tid of the parent of the term.
     *
     * @return int
     *   The tid of the new term.
     */
    public function createTerm($vid, $title, $parent = 0)
    {
        $options = array('vid' => $vid, 'name' => $title);
        if ($parent) {
            $options['parent'] = $parent;
        }

        $term = (object) $options;
        taxonomy_term_save($term);

        return $term->tid;
    }

    /**
     * Creates a hierarchical structure of taxonomy terms.
     *
     * Pay attention that the terms created start always from 1 and not 0,
     * as it has more sense when looking in-depth elements. For example,
     * if you want the second child of the third element, you can specify
     * $terms[3][2] instead of $terms[2][1].
     *
     * @param int $vid
     *   The id of the vocabulary in which to create the term.
     * @param int $depth
     *   How many levels to create. 1 is a flat hierarchy.
     * @param int $number
     *   How many terms to create in each level.
     * @param int $parent
     *   The parent of the structure terms.
     * @param string $prefix
     *   The prefix to use on terms. Meant for internal use.
     *
     * @return array
     *   An array of term ids and children.
     *   Every element returned has numeric keys for the child elements
     *   and a #tid key for his own tid. Children can be easily accessed
     *   recursively with element_children() or element_child() functions.
     */
    public function createHierarchicalStructure($vid, $depth, $number, $parent = 0, $prefix = '')
    {
        $terms = array();

        for ($i = 1; $i <= $number; $i++) {
            $title = $prefix . $i;
            $tid = $this->createTerm($vid, $title, $parent);
            $terms[$i] = array(
                '#tid' => $tid,
            );

            // Create children if depth is specified.
            if ($depth > 1) {
                $terms[$i] += $this->createHierarchicalStructure($vid, $depth - 1, $number, $tid, "$title-");
            }
        }

        return $terms;
    }

    /**
     * Change the language of the term.
     *
     * @param int $tid
     *   The term id.
     * @param $new_lang
     *   The new language for the term.
     */
    public function changeTermLanguage($tid, $new_lang)
    {
        $term = taxonomy_term_load($tid);

        // Get the language of each field.
        $field_langs = field_language('taxonomy_term', $term);
        // Go through ALL field of this node.
        foreach ($field_langs as $field => $lang) {
            // If the field is in the wrong language get all field values.
            if ($lang != $new_lang) {
                $items = field_get_items('taxonomy_term', $term, $field, $lang);

                // Give the field the new language and remove the old language.
                if (!empty($items)) {
                    $term->{$field}[$new_lang] = $items;
                    unset($term->{$field}[$lang]);
                }
            }
        }
        // Set the new term language.
        $term->language = $new_lang;
        taxonomy_term_save($term);
    }
}
