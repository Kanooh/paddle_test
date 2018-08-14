<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Element\PanelsContentType\ListingPanelsContentType.
 */

namespace Kanooh\Paddle\Pages\Element\PanelsContentType;

use Kanooh\Paddle\Pages\Element\Element;
use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Form\RadioButton;
use Kanooh\Paddle\Pages\Element\Form\Text;

/**
 * The 'Listing' Panels content type.
 *
 * @property Checkbox $paddleAdvancedSearchPageCheckBox
 *   The checkbox for the advanced search page content type.
 * @property Checkbox $basicPageCheckBox
 *   The checkbox for the basic page content type.
 * @property Checkbox $calendarItemCheckBox
 *   The checkbox for the calendar item content type.
 * @property Checkbox $contactPersonCheckBox
 *   The checkbox for the contact person content type.
 * @property AutoCompletedText $filterGeneralTags
 *   The autocomplete text field to filter by general tags.
 * @property AutoCompletedText $filterTags
 *   The autocomplete text field to filter by tags.
 * @property Checkbox $landingPageCheckBox
 *   The checkbox for the landing page content type.
 * @property Checkbox $newsItemCheckBox
 *   The checkbox for the news item content type.
 * @property Checkbox $newsletterCheckBox
 *   The checkbox for the newsletter content type.
 * @property Checkbox $organizationalUnitCheckBox
 *   The checkbox for the organizational unit content type.
 * @property Checkbox $paddleMapsPageCheckBox
 *   The checkbox for the maps page content type.
 * @property Checkbox $paddleOverviewPageCheckBox
 *   The checkbox for the overview page content type.
 * @property Checkbox $quizPageCheckBox
 *   The checkbox for the quiz page content type.
 * @property Checkbox $paddleFormbuilderPageCheckBox
 *   The checkbox for the Formbuilder page content type.
 * @property Checkbox $paddleProductCheckBox
 * @property Checkbox $paddlePollPageCheckBox
 *   The checkbox for the Poll page content type.
 * @property Checkbox $offerCheckBox
 * @property Checkbox $paddleCirroPageCheckBox
 * @property Checkbox $paddleEblPageCheckBox
 * @property RadioButton $sortingChronologicalCreatedAsc
 *   The radio button to select the chronological by created date sorting mode
 *   (asc).
 * @property Checkbox $simpleContactPageCheckBox
 *   The checkbox for the simple contact page content type.
 * @property RadioButton $sortingChronologicalCreatedDesc
 *   The radio button to select the chronological by created date sorting mode
 *   (desc).
 * @property RadioButton $sortingRateAsc
 *   The radio button to select the rate sorting mode (asc).
 * @property RadioButton $sortingRateDesc
 *   The radio button to select the rate sorting mode (desc).
 * @property RadioButton $viewModeListingTeaserRadioButton
 *   The radio button to select the teaser view mode.
 * @property RadioButton $viewModeNewsShortRadioButton
 *   The radio button to select the news short view mode.
 * @property RadioButton $viewModeNewsExtendedRadioButton
 *   The radio button to select the news extended view mode.
 * @property RadioButton $viewModeNewsDetailedRadioButton
 *   The radio button to select the news detailed view mode.
 */
class ListingPanelsContentType extends SectionedPanelsContentType
{

    /**
     * {@inheritdoc}
     */
    const TYPE = 'listing';

    /**
     * {@inheritdoc}
     */
    const TITLE = 'Add listing';

    /**
     * {@inheritdoc}
     */
    const DESCRIPTION = 'Add listing.';

    /**
     * The general vocabulary tags to filter on.
     *
     * @var string
     */
    public $general_vocabulary_tags;

    /**
     * The tags tags to filter on.
     *
     * @var string
     */
    public $tags_tags;

    /**
     * The content types to filter on.
     *
     * @var array
     */
    public $content_types;

    /**
     * The display of the nodes to use.
     *
     * @var string
     *   One of the following:
     *   - "listing_title"
     *   - "listing_teaser"
     */
    public $display;

    /**
     * The number of items to display.
     *
     * @var int
     */
    public $number_of_items;

    /**
     * The sorting of the nodes shown in the listing.
     *
     * @var string
     *   One of the following:
     *   - "stamp_desc"
     *   - "stamp_asc"
     *   - "title_asc"
     *   - "title_desc"
     */
    public $sorting;

    /**
     * {@inheritdoc}
     *
     * @todo Refactor to use the Form class.
     */
    public function fillInConfigurationForm(Element $element = null)
    {
        // We use the default settings at this moment.
        $this->disableSections();
    }

    /**
     * {@inheritdoc}
     */
    public function getForm(Element $element = null)
    {
        // @todo Implement.
    }

    public function __get($name)
    {
        switch ($name) {
            case 'paddleAdvancedSearchPageCheckBox':
                return new Checkbox($this->webdriver, $this->webdriver->byXPath('//input[@name="content_types[paddle_advanced_search_page]"]'));
            case 'basicPageCheckBox':
                return new Checkbox($this->webdriver, $this->webdriver->byXPath('//input[@name="content_types[basic_page]"]'));
            case 'calendarItemCheckBox':
                return new Checkbox($this->webdriver, $this->webdriver->byXPath('//input[@name="content_types[calendar_item]"]'));
            case 'contactPersonCheckBox':
                return new Checkbox($this->webdriver, $this->webdriver->byXPath('//input[@name="content_types[contact_person]"]'));
            case 'filterGeneralTags':
                return new AutoCompletedText($this->webdriver, $this->webdriver->byXPath('//input[@name="terms[paddle_general]"]'));
            case 'filterTags':
                return new AutoCompletedText($this->webdriver, $this->webdriver->byXPath('//input[@name="terms[paddle_tags]"]'));
            case 'landingPageCheckBox':
                return new Checkbox($this->webdriver, $this->webdriver->byXPath('//input[@name="content_types[landing_page]"]'));
            case 'newsItemCheckBox':
                return new Checkbox($this->webdriver, $this->webdriver->byXPath('//input[@name="content_types[news_item]"]'));
            case 'newsletterCheckBox':
                return new Checkbox($this->webdriver, $this->webdriver->byXPath('//input[@name="content_types[newsletter]"]'));
            case 'organizationalUnitCheckBox':
                return new Checkbox($this->webdriver, $this->webdriver->byXPath('//input[@name="content_types[organizational_unit]"]'));
            case 'paddleMapsPageCheckBox':
                return new Checkbox($this->webdriver, $this->webdriver->byXPath('//input[@name="content_types[paddle_maps_page]"]'));
            case 'paddleOverviewPageCheckBox':
                return new Checkbox($this->webdriver, $this->webdriver->byXPath('//input[@name="content_types[paddle_overview_page]"]'));
            case 'quizPageCheckBox':
                return new Checkbox($this->webdriver, $this->webdriver->byXPath('//input[@name="content_types[quiz_page]"]'));
            case 'paddleFormbuilderPageCheckBox':
                return new Checkbox($this->webdriver, $this->webdriver->byXPath('//input[@name="content_types[paddle_formbuilder_page]"]'));
            case 'paddleProductCheckBox':
                return new Checkbox($this->webdriver, $this->webdriver->byXPath('//input[@name="content_types[paddle_product]"]'));
            case 'pollCheckBox':
                $xpath = '//input[@name="content_types[poll]"]';
                return new Checkbox($this->webdriver, $this->webdriver->byXPath($xpath));
            case 'offerCheckBox':
                return new Checkbox($this->webdriver, $this->webdriver->byXPath('//input[@name="content_types[offer]"]'));
            case 'paddleCirroPageCheckBox':
                return new Checkbox($this->webdriver, $this->webdriver->byXPath('//input[@name="content_types[paddle_cirro_page]"]'));
            case 'paddlePublicationCheckBox':
                return new Checkbox($this->webdriver, $this->webdriver->byXPath('//input[@name="content_types[paddle_publication]"]'));
            case 'paddleEblPageCheckBox':
                return new Checkbox($this->webdriver, $this->webdriver->byXPath('//input[@name="content_types[paddle_ebl_page]"]'));
            case 'sortingChronologicalCreatedAsc':
                return new RadioButton($this->webdriver, $this->webdriver->byXPath('//input[@name="sorting_type" and @value="created_asc"]'));
            case 'simpleContactPageCheckBox':
                return new Checkbox($this->webdriver, $this->webdriver->byXPath('//input[@name="content_types[simple_contact_page]"]'));
            case 'sortingChronologicalCreatedDesc':
                return new RadioButton($this->webdriver, $this->webdriver->byXPath('//input[@name="sorting_type" and @value="created_desc"]'));
            case 'sortingRateDesc':
                return new RadioButton($this->webdriver, $this->webdriver->byXPath('//input[@name="sorting_type" and @value="value_desc"]'));
            case 'sortingRateAsc':
                return new RadioButton($this->webdriver, $this->webdriver->byXPath('//input[@name="sorting_type" and @value="value_asc"]'));
            case 'viewModeListingTeaserRadioButton':
                return new RadioButton($this->webdriver, $this->webdriver->byXpath('//input[@name="view_mode" and @value="listing_teaser"]'));
            case 'viewModeNewsShortRadioButton':
                return new RadioButton($this->webdriver, $this->webdriver->byXpath('//input[@name="view_mode" and @value="news_short"]'));
            case 'viewModeNewsExtendedRadioButton':
                return new RadioButton($this->webdriver, $this->webdriver->byXpath('//input[@name="view_mode" and @value="news_extended"]'));
            case 'viewModeNewsDetailedRadioButton':
                $xpath = '//input[@name="view_mode" and @value="news_detailed"]';
                return new RadioButton($this->webdriver, $this->webdriver->byXpath($xpath));
        }

        return parent::__get($name);
    }
}
