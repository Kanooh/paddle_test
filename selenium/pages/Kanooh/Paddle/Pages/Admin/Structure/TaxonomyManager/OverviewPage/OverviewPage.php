<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Structure\TaxonomyManager\OverviewPage\OverviewPage.
 */

namespace Kanooh\Paddle\Pages\Admin\Structure\TaxonomyManager\OverviewPage;

use Kanooh\Paddle\Pages\AdminPage;

/**
 * The Taxonomy Overview page of the Paddle Taxonomy Manager module.
 *
 * @property OverviewPageContextualToolbar $contextualToolbar
 *   The contextual toolbar.
 * @property OverviewPageCreateTermModal $createTermModal
 *   The modal dialog used to create terms.
 * @property OverviewPageForm $form
 *   The form holding the submit button.
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element[] $vocabularyLinks
 *   Menu links to the vocabularies.
 * @property OverviewPageTable $vocabularyTable
 *   The table holding the terms.
 */
class OverviewPage extends AdminPage
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
     * {@inheritdoc}
     */
    protected $path = 'admin/structure/taxonomy_manager/%';

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch ($property) {
            case 'contextualToolbar':
                return new OverviewPageContextualToolbar($this->webdriver);
            case 'createTermModal':
                return new OverviewPageCreateTermModal($this->webdriver);
            case 'vocabularyTable':
                return new OverviewPageTable($this->webdriver);
            case 'vocabularyLinks':
                return $this->getVocabularyLinks();
            case 'form':
                return new OverviewPageForm($this->webdriver, $this->webdriver->byId('paddle-taxonomy-manager-vocabulary-overview-form'));
        }
        return parent::__get($property);
    }

    /**
     * Get the contextual toolbar.
     *
     * @return OverviewPageContextualToolbar
     */
    public function getContextualToolbar()
    {
        if (!isset($this->contextualToolbar)) {
            $this->contextualToolbar = new OverviewPageContextualToolbar($this->webdriver);
        }
        return $this->contextualToolbar;
    }

    /**
     * Get the modal dialog used to create terms.
     *
     * @return OverviewPageCreateTermModal
     */
    public function getCreateTermModal()
    {
        if (!isset($this->createTermModal)) {
            $this->createTermModal = new OverviewPageCreateTermModal($this->webdriver);
        }
        return $this->createTermModal;
    }

    /**
     * Get the table holding the terms.
     *
     * @return OverviewPageTable
     */
    public function getVocabularyTable()
    {
        if (!isset($this->vocabularyTable)) {
            $this->vocabularyTable = new OverviewPageTable($this->webdriver);
        }
        return $this->vocabularyTable;
    }

    /**
     * Returns a list of the vocabulary menu links.
     *
     * This list is dynamic and missing semantic classes to identify each link,
     * so these cannot be represented by the Links class.
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element[]
     *   List of Selenium elements representing the links.
     */
    public function getVocabularyLinks()
    {
        $xpath = '//div[@id="sidebar_left"]//li[contains(@class, "menu-item")]//a';
        $criteria = $this->webdriver->using('xpath')->value($xpath);
        return $this->webdriver->elements($criteria);
    }

    /**
     * Creates a new term or tag.
     *
     * @param array $values
     *   The values we need to create the term - 'name'(mandatory),
     *   'description', 'parent'. The name of the form field must be the key of
     *   each array element, the field value - the array value.
     * @param array $parents
     *   Array of tids which are parents of the new term/tag. Used to retrieve
     *   the tid.
     *
     * @return int
     *   The tid of the newly created term/tag.
     */
    public function createTerm($values, array $parents = array())
    {
        if (!$values['name']) {
            // The "name" field value is set - the term cannot be saved.
            return 0;
        }

        $term_name = $values['name'];

        // Get the form from the page, so we can wait until it's rebuilt later.
        $form = $this->form;

        // Open the modal by clicking on "Create term" button.
        $this->getContextualToolbar()->buttonCreateTerm->click();

        $this->getCreateTermModal()->initializeFormElements(array('name', 'description', 'parent'));
        $this->getCreateTermModal()->formElementName->value($term_name);
        if (isset($values['description'])) {
            $this->getCreateTermModal()->formElementDescription->value($values['description']);
        }
        if (isset($values['parent'])) {
            $select = $this->webdriver->select($this->getCreateTermModal()->formElementParent);
            $select->selectOptionByValue($values['parent']);
        }

        $this->getCreateTermModal()->submit();
        $this->getCreateTermModal()->waitUntilClosed();

        // Wait for the form to rebuild. Can't use $this->form here as it would
        // create a new form object with no previous build id to compare
        // against.
        $form->waitUntilFormBuildIdChanges();

        // Try to retrieve the tid. If the term has parents it will not be
        // displayed, so we need to open the level below it's parent and
        // grandparents.
        foreach ($parents as $parent) {
            // Get the parent row.
            $parent_term_row = $this->getVocabularyTable()->getTermRowsByTid($parent);
            // Open the link.
            $parent_term_row->linkShowChildTerms->click();
            $parent_term_row->waitUntilChildTermsArePresent();
        }
        $this->webdriver->waitUntilTextIsPresent($term_name);
        $term_row = $this->getVocabularyTable()->getTermRowsByTitle($term_name);
        return $term_row->termId;
    }

    /**
     * Delete an existing term or tag.
     *
     * @param int $tid
     *   The id of the term to delete.
     */
    public function deleteTerm($tid)
    {
        $row = $this->getVocabularyTable()->getTermRowsByTid($tid);
        $row->linkDelete->click();
        $this->webdriver->waitUntilTextIsPresent('Are you sure you want to delete the term');
        $row->deleteModal->submit();
        $row->deleteModal->waitUntilClosed();
    }
}
