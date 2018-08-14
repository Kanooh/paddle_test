<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\TermReferenceTree\TermReferenceTree.
 */

namespace Kanooh\Paddle\Pages\Element\TermReferenceTree;

use Kanooh\Paddle\Pages\Element\Element;

/**
 * A taxonomy term reference tree.
 */
abstract class TermReferenceTree extends Element
{
    /**
     * Returns an array of child terms for a given parent, or the root level.
     *
     * @param TermReferenceTreeElement $parent
     *
     * @return TermReferenceTreeElement[]
     */
    public function getChildTerms($parent = null)
    {
        if (empty($parent)) {
            $parent_xpath = $this->getXPathSelector()
                . '//div[contains(concat(" ", normalize-space(@class), " "), " term-reference-tree ")]';
            $parent = $this->webdriver->element($this->webdriver->using('xpath')->value($parent_xpath));
        } else {
            $parent = $parent->getElement();
        }

        $children_xpath = './ul[contains(@class, "term-reference-tree-level")]/li';
        $elements = $parent->elements($parent->using('xpath')->value($children_xpath));
        $children = array();

        foreach ($elements as $element) {
            $term_element = new TermReferenceTreeElement($element);
            $children[$term_element->getTid()] = $term_element;
        }

        return $children;
    }

    /**
     * Returns an array with all visible terms in the tree, keyed by their id.
     *
     * @return TermReferenceTreeElement[]
     */
    public function getAllVisibleTerms()
    {
        $xpath = $this->getXPathSelector() . '//ul[contains(@class, "term-reference-tree-level")]//li';
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));

        $terms = array();
        foreach ($elements as $element) {
            $term = new TermReferenceTreeElement($element);
            $terms[$term->getTid()] = $term;
        }
        return $terms;
    }

    /**
     * Returns a term based on the term id given. The term should be visible.
     *
     * @param int $tid
     *   Term id.
     *
     * @return TermReferenceTreeElement
     */
    public function getTermById($tid)
    {
        $xpath = $this->getXPathSelector() .
            '//li[./div[contains(@class, "form-type-checkbox")]/input[@type="checkbox"][@value="' . $tid . '"]]';
        $element = $this->webdriver->byXPath($xpath);
        return new TermReferenceTreeElement($element);
    }

    /**
     * Selects the term with the given term ID.
     *
     * Note that this will also select the term's parent and child terms, if the
     * tree is configured to automatically select parent/child terms.
     *
     * @param int $tid
     *   The term ID of the term to select.
     */
    public function selectTerm($tid)
    {
        $term = $this->getTermById($tid);
        $term->select();
    }

    /**
     * Deselects the term with the given term ID.
     *
     * Note that this will also select the term's parent and child terms, if the
     * tree is configured to automatically select parent/child terms.
     *
     * @param int $tid
     *   The term ID of the term to deselect.
     */
    public function deselectTerm($tid)
    {
        $term = $this->getTermById($tid);
        $term->deselect();
    }

    /**
     * Clears all term checkboxes in the tree.
     */
    public function deselectAllTerms()
    {
        $this->deselectChildTerms(null, true);
    }

    /**
     * Deselects all child terms of the supplied parent term.
     *
     * @param TermReferenceTreeElement $parent
     *   Parent term, for which to deselect the child terms. If no parent is
     *   specified, the root level terms will be deselected.
     * @param bool $recursive
     *   If set to true (default) all child terms' children will be deselected
     *   as well, instead of just the direct child terms.
     */
    public function deselectChildTerms($parent = null, $recursive = true)
    {
        $elements = $this->getChildTerms($parent);
        foreach ($elements as $element) {
            $checkbox = $element->getCheckbox();

            /* @var $checkbox \PHPUnit_Extensions_Selenium2TestCase_Element */
            if ($checkbox->selected()) {
                $checkbox->click();
            }

            // Clear the children of the current element as well.
            if ($recursive && $element->hasChildren()) {
                $this->deselectChildTerms($element);
            }
        }
    }

    /**
     * Expands all terms in the tree.
     */
    public function expandAllTerms()
    {
        $this->expandChildTerms(null, true);
    }

    /**
     * Expands all child terms of a term, so their child terms are visible.
     *
     * @param TermReferenceTreeElement $parent
     *   Parent term, for which to expand the child terms. If no parent is
     *   specified, the root level terms will be expanded.
     * @param bool $recursive
     *   If set to true (default) all child terms' children will be expanded as
     *   well, instead of just the direct child terms.
     */
    public function expandChildTerms($parent = null, $recursive = true)
    {
        $elements = $this->getChildTerms($parent);
        foreach ($elements as $element) {
            // Expand the term if it has children AND the children are hidden.
            if ($element->hasChildren() && !$element->hasVisibleChildren()) {
                $element->getExpandButton()->click();

                // Also expand the child elements.
                if ($recursive) {
                    $this->expandChildTerms($element);
                }
            }
        }
    }

    /**
     * Checks if a term is visible in the tree.
     * @param int $tid
     *   The TID of the wanted term.
     * @return bool
     *   True if the term is shown, otherwise false.
     */
    public function checkTermVisibile($tid)
    {
        $xpath = $this->getXPathSelector() . '//li[./div[contains(@class, "form-type-checkbox")]/input[@type="checkbox"][@value="' . $tid . '"]]//ul[@style="display: none;"]';
        $elements = $this->webdriver->elements($this->webdriver->using('xpath')->value($xpath));
        return (bool) count($elements);
    }
}
