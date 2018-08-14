<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\EditPageRandomFiller.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage;

use Drupal\Component\Utility\Random;

/**
 * Page to edit a page.
 */
class EditPageRandomFiller
{
    /**
     * Title field flag.
     *
     * @var int
     */
    const TITLE_FIELD = 1;

    /**
     * Publish on date field flag.
     *
     * @var int
     */
    const PUBLISH_ON_FIELD = 2;

    /**
     * Unpublish on date field flag.
     *
     * @var int
     */
    const UNPUBLISH_ON_FIELD = 4;

    /**
     * Tags field flag.
     *
     * @var int
     */
    const TAGS_FIELD = 0b1000;

    /**
     * General tags field flag.
     *
     * @var int
     */
    const GENERAL_TAGS_FIELD = 0b10000;

    /**
     * Required fields flag.
     *
     * @var int
     */
    const REQUIRED_FIELDS = self::TITLE_FIELD;

    /**
     * Fields that should be randomized and filled.
     *
     * @var int
     */
    public $targetFields = self::REQUIRED_FIELDS;

    /**
     * The page to which this random filler is coupled.
     *
     * @var EditPage
     */
    protected $page;

    /**
     * The publish on date.
     *
     * @var string
     */
    public $publishOnDate;

    /**
     * The publish on time.
     *
     * @var string
     */
    public $publishOnTime;

    /**
     * The random data generation class.
     *
     * @var Random
     */
    protected $randomGenerator;

    /**
     * The title field which needs to be filled
     *
     * @var string
     */
    public $title;

    /**
     * The unpublish on date.
     *
     * @var string
     */
    public $unpublishOnDate;

    /**
     * The unpublish on time.
     *
     * @var string
     */
    public $unpublishOnTime;

    /**
     * The tags linked to the page.
     *
     * @var array
     */
    public $tags;

    /**
     * The general tags linked to the page.
     *
     * @var array
     */
    public $generalTags;

    /**
     * Constructs an EditPageRandomFiller object.
     *
     * This can be used for node edit pages.
     *
     * @param EditPage $page
     *   The page for which to fill the fields.
     */
    public function __construct(EditPage $page)
    {
        $this->page = $page;
        $this->randomGenerator = new Random();
        $this->resetFieldValues();
    }

    /**
     * Sets the target fields.
     *
     * @param int $targets
     *   Fields to be targeted, using the field flags.
     */
    public function setTargetFields($targets)
    {
        $this->targetFields = $targets;
    }

    /**
     * Returns the target fields.
     *
     * @return int
     */
    public function getTargetFields()
    {
        return $this->targetFields;
    }

    /**
     * Reset all field values.
     */
    public function resetFieldValues()
    {
        $this->title = '';
        $this->publishOnDate = '';
        $this->publishOnTime = '';
        $this->unpublishOnDate = '';
        $this->unpublishOnTime = '';
        $this->tags = array();
        $this->generalTags = array();
    }

    /**
     * Randomize the fields of the form.
     *
     * @return $this
     *   Returns the filler.
     */
    public function randomize()
    {
        // Start with a clean slate.
        $this->resetFieldValues();

        $targets = $this->getTargetFields();

        if ($targets & self::TITLE_FIELD) {
            $this->title = $this->randomGenerator->name(8);
        }

        if ($targets & self::PUBLISH_ON_FIELD) {
            // Use a random date between 1 and 7 days from now for the publication
            // date.
            $publish_offset = rand(1, 7);
            $this->publishOnDate = date('d/m/Y', strtotime("+$publish_offset day"));
            $this->publishOnTime = date('h:i:s', gmmktime(rand(0, 23), rand(0, 59), rand(0, 59)));
        }

        if ($targets & self::UNPUBLISH_ON_FIELD) {
            // For the unpublication date, use a date between 1 and 7 days after the
            // publication date.
            $unpublish_offset = rand(1, 7);
            $unpublish_offset += isset($publish_offset) ? $publish_offset : 0;
            $this->unpublishOnDate = date('d/m/Y', strtotime("+$unpublish_offset day"));
            $this->unpublishOnTime = date('h:i:s', gmmktime(rand(0, 23), rand(0, 59), rand(0, 59)));
        }

        if ($targets & self::TAGS_FIELD) {
            for ($i = 0; $i < rand(1, 4); $i++) {
                // All tags have their first letter capitalized.
                $this->tags[] = ucfirst($this->randomGenerator->name(8));
            }
        }

        if ($targets & self::GENERAL_TAGS_FIELD) {
            /* @var $elements \PHPUnit_Extensions_Selenium2TestCase_Element[] */
            $elements = $this->page->getGeneralVocabularyTermElements();
            $i = rand(1, 4);
            while (!empty($elements) && $i--) {
                $key = array_rand($elements);
                $this->generalTags[] = $elements[$key]->attribute('value');
                unset($elements[$key]);
            }
        }

        return $this;
    }

    /**
     * Fills the form fields for a specific page.
     *
     * This can be used for node edit pages.
     */
    public function fill()
    {

        $targets = $this->getTargetFields();

        if ($targets & self::TITLE_FIELD) {
            $this->page->title->clear();
            $this->page->title->value($this->title);
        }

        // Set scheduling options.

        // Open the fieldset if it is not yet open. This is a bit tricky because
        // the class that indicates whether it is open or not is on the
        // container, while the element that needs to be clicked is the title
        // inside the container. We also need to wait until the fieldset is
        // fully open before interacting with the fields inside, or Firefox
        // won't be able to find them.
        if (($targets & self::PUBLISH_ON_FIELD || $targets & self::UNPUBLISH_ON_FIELD) &&
            in_array('folded', explode(' ', $this->page->schedulerOptionsContainer->attribute('class')))) {
            $this->page->moveto($this->page->schedulerOptionsContainer);
            $this->page->schedulerOptionsTitle->click();
            $this->page->waitUntilFieldsetIsOpen($this->page->schedulerOptionsContainer);
        }

        if ($targets & self::PUBLISH_ON_FIELD) {
            $this->fillScheduleField('publish');
        }

        if ($targets & self::UNPUBLISH_ON_FIELD) {
            $this->fillScheduleField('unpublish');
        }

        if ($targets & self::TAGS_FIELD) {
            $this->fillTagsField();
        }

        if ($targets & self::GENERAL_TAGS_FIELD) {
            $this->fillGeneralVocabularyField();
        }
    }

    /**
     * Fills the publish or unpublish fields.
     *
     * @param string $type
     *   'publish' or 'unpublish'
     */
    protected function fillScheduleField($type)
    {
        $fields = array(
            $type . 'OnDate',
            $type . 'OnTime',
        );

        foreach ($fields as $field) {
            // We need to do some trickery here to trigger the javascript that
            // makes the form fields available and hides the date popups.
            // Clicking the fields before setting the values seems to work.
            $this->page->moveto($this->page->$field);
            $this->page->$field->click();
            $this->page->$field->clear();
            $this->page->$field->value($this->$field);
        }
    }

    /**
     * Fills the tags field.
     */
    protected function fillTagsField()
    {
        foreach ($this->tags as $tag) {
            $this->page->tags->value($tag);
            $this->page->tagsAddButton->click();
            $this->page->waitUntilTagIsDisplayed($tag);
        }
    }

    /**
     * Fills the general vocabulary fields.
     */
    public function fillGeneralVocabularyField()
    {
        // Expand the full tree first, so we can find all terms. Then deselect
        // all terms, before selecting the ones we want.
        $this->page->generalVocabularyTermReferenceTree->expandAllTerms();
        $this->page->generalVocabularyTermReferenceTree->deselectAllTerms();

        // Loop over the term ids, get the terms from the tree, and select them.
        foreach ($this->generalTags as $tid) {
            $term = $this->page->generalVocabularyTermReferenceTree->getTermById($tid);
            $term->select();
        }
    }
}
