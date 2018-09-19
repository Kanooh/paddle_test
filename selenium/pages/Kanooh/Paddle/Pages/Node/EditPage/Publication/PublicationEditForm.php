<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Node\EditPage\Publication\PublicationEditForm.
 */

namespace Kanooh\Paddle\Pages\Node\EditPage\Publication;

use Kanooh\Paddle\Pages\Element\Form\AutoCompletedText;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Publication\AuthorsTable;
use Kanooh\Paddle\Pages\Element\Publication\RelatedDocumentsTable;
use Kanooh\Paddle\Pages\Element\Form\Text;
use Kanooh\Paddle\Pages\Element\Form\Select;
use Kanooh\Paddle\Pages\Element\Publication\RelatedLinksTable;

/**
 * Class representing the publication edit form.
 *
 * @property AuthorsTable $authorsTable
 * @property AutoCompletedText $keywords
 * @property \PHPUnit_Extensions_Selenium2TestCase_Element $keywordsAddButton
 * @property AutoCompletedText $meshTerms
 * @property AutoCompletedText $publicationYear
 * @property AutoCompletedText $collections
 * @property Text $number
 * @property Text $type
 * @property Text $publisher
 * @property Text $placePublished
 * @property Text $publicationLanguage
 * @property Text $datePublished
 * @property Text $kceNumber
 * @property Text $legalDepotNumber
 * @property Text $study
 * @property Text $documentsLanguage
 * @property Select $publicationType
 * @property RelatedDocumentsTable $relatedDocumentsTable
 * @property Text $url
 * @property RelatedLinksTable $relatedLinksTable
 */
class PublicationEditForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        switch ($name) {
            case 'authorsTable':
                return new AuthorsTable($this->webdriver, '//table[contains(@id, "field-paddle-kce-authors-values")]');
                break;
            case 'keywords':
                return new AutoCompletedText($this->webdriver, $this->element->byName('field_paddle_kce_keywords[und][term_entry]'));
                break;
            case 'keywordsAddButton':
                return $this->element->byXPath('.//div[contains(@class, "field-name-field-paddle-kce-keywords")]//input[@type = "submit" and @name = "op"]');
                break;
            case 'meshTerms':
                return new AutoCompletedText($this->webdriver, $this->element->byName('field_paddle_kce_mesh_terms[und][term_entry]'));
                break;
            case 'meshTermsAddButton':
                return $this->element->byXPath('.//div[contains(@class, "field-name-field-paddle-kce-mesh-terms")]//input[@type = "submit" and @name = "op"]');
                break;
            case 'publicationType':
                return new Select($this->webdriver, $this->element->byName('field_paddle_kce_publ_type[und]'));
                break;
            case 'publicationYear':
                return new AutoCompletedText($this->webdriver, $this->element->byName('field_paddle_kce_publication_y[und]'));
                break;
            case 'collections':
                return new AutoCompletedText($this->webdriver, $this->element->byName('field_paddle_kce_collections[und]'));
                break;
            case 'number':
                return new Text($this->webdriver, $this->element->byName('field_paddle_kce_number[und][0][value]'));
                break;
            case 'type':
                return new Text($this->webdriver, $this->element->byName('field_paddle_kce_type[und][0][value]'));
                break;
            case 'publisher':
                return new Text($this->webdriver, $this->element->byName('field_paddle_kce_publisher[und][0][value]'));
                break;
            case 'placePublished':
                return new Text($this->webdriver, $this->element->byName('field_paddle_kce_place_published[und][0][value]'));
                break;
            case 'publicationLanguage':
                return new Text($this->webdriver, $this->element->byName('field_paddle_kce_publication_lan[und][0][value]'));
                break;
            case 'legalDepotNumber':
                return new Text($this->webdriver, $this->element->byName('field_paddle_kce_depot_number[und][0][value]'));
                break;
            case 'study':
                return new Text($this->webdriver, $this->element->byName('field_paddle_kce_study[und][0][value]'));
                break;
            case 'documentsLanguage':
                return new Text($this->webdriver, $this->element->byName('field_paddle_kce_doc_lang[und][0][value]'));
                break;
            case 'relatedDocumentsTable':
                return new RelatedDocumentsTable($this->webdriver, '//table[contains(@id, "field-paddle-kce-related-docs-values")]');
                break;
            case 'url':
                return new Text($this->webdriver, $this->element->byName('field_paddle_kce_url[und][0][value]'));
                break;
            case 'relatedLinksTable':
                return new RelatedLinksTable($this->webdriver, '//table[contains(@id, "field-paddle-kce-related-links-values")]');
                break;
            case 'datePublished':
                return new Text($this->webdriver, $this->element->byName('field_paddle_kce_date_published[und][0][value]'));
                break;
            case 'kceNumber':
                return new Text($this->webdriver, $this->element->byName('field_paddle_kce_number[und][0][value]'));
        }
        throw new FormFieldNotDefinedException($name);
    }
}
