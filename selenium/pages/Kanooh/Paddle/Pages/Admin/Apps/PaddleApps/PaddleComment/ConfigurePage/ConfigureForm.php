<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleComment\ConfigurePage\ConfigureForm.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleComment\ConfigurePage;

use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;
use Kanooh\Paddle\Pages\Element\Form\RadioButton;

/**
 * Class representing the Paddle Comment configuration form.
 *
 * @property RadioButton $skipApproval
 *   The form element representing the 'Skip approval' radio option.
 * @property RadioButton $requireApproval
 *   The form element representing the 'Require approval' radio option.
 * @property Checkbox $typePaddleAdvancedSearchPage
 *   The form element representing the 'Paddle advanced search page' content type checkbox.
 * @property Checkbox $typeBasicPage
 *   The form element representing the 'Basic page' content type checkbox.
 * @property Checkbox $typeLandingPage
 *   The form element representing the 'Landing page' content type checkbox.
 * @property Checkbox $typePaddleOverviewPage
 *   The form element representing the 'Overview page' content type checkbox.
 * @property Checkbox $typeOrganizationUnit
 *   The form element representing the 'Organization Unit' content type checkbox.
 * @property Checkbox $typePaddleMapsPage
 *   The form element representing the 'Paddle maps page' content type checkbox.
 * @property Checkbox $typeNewsItem
 *   The form element representing the 'News' content type checkbox.
 * @property Checkbox $typeContactPerson
 *   The form element representing the 'Contact person' content type checkbox.
 * @property Checkbox $typeSimpleContactPage
 *   The form element representing the 'Simple Contact Page' content type checkbox.
 * @property Checkbox $typePaddleFormbuilderPage
 *   The form element representing the 'Formbuilder' content type checkbox.
 * @property Checkbox $typeCalendarItem
 *   The form element representing the 'Calendar' content type checkbox.
 * @property Checkbox $typeQuiz
 *   The form element representing the 'Quiz' content type checkbox.
 * @property Checkbox $typeNewsletter
 *   The form element representing the 'Newsletter' content type checkbox.
 * @property Checkbox $typePoll
 *   The form element representing the 'Poll' content type checkbox.
 * @property Checkbox $typePaddleCirroPage
 * @property Checkbox $typePaddleProduct
 * @property Checkbox $typeOffer
 * @property Checkbox $typePaddlePublication
 * @property Checkbox $typePaddleEblPage
 */
class ConfigureForm extends Form
{
    /**
     * {@inheritdoc}
     */
    public function __get($name)
    {
        $base_id = 'edit-paddle-comment-content-types-';
        switch ($name) {
            case 'skipApproval':
                return new RadioButton($this->webdriver, $this->webdriver->byId('edit-paddle-comment-skip-approval-1'));
                break;
            case 'requireApproval':
                return new RadioButton($this->webdriver, $this->webdriver->byId('edit-paddle-comment-skip-approval-0'));
                break;
            case 'typePaddleAdvancedSearchPage':
                return new Checkbox($this->webdriver, $this->webdriver->byId($base_id . 'paddle-advanced-search-page'));
                break;
            case 'typeBasicPage':
                return new Checkbox($this->webdriver, $this->webdriver->byId($base_id . 'basic-page'));
                break;
            case 'typeLandingPage':
                return new Checkbox($this->webdriver, $this->webdriver->byId($base_id . 'landing-page'));
                break;
            case 'typePaddleOverviewPage':
                return new Checkbox($this->webdriver, $this->webdriver->byId($base_id . 'paddle-overview-page'));
                break;
            case 'typeOrganizationalUnit':
                return new Checkbox($this->webdriver, $this->webdriver->byId($base_id . 'organizational-unit'));
                break;
            case 'typePaddleMapsPage':
                return new Checkbox($this->webdriver, $this->webdriver->byId($base_id . 'paddle-maps-page'));
                break;
            case 'typeNewsItem':
                return new Checkbox($this->webdriver, $this->webdriver->byId($base_id . 'news-item'));
                break;
            case 'typeContactPerson':
                return new Checkbox($this->webdriver, $this->webdriver->byId($base_id . 'contact-person'));
                break;
            case 'typeSimpleContactPage':
                return new Checkbox($this->webdriver, $this->webdriver->byId($base_id . 'simple-contact-page'));
                break;
            case 'typePaddleFormbuilderPage':
                return new Checkbox($this->webdriver, $this->webdriver->byId($base_id . 'paddle-formbuilder-page'));
                break;
            case 'typeCalendarItem':
                return new Checkbox($this->webdriver, $this->webdriver->byId($base_id . 'calendar-item'));
                break;
            case 'typeQuizPage':
                return new Checkbox($this->webdriver, $this->webdriver->byId($base_id . 'quiz-page'));
                break;
            case 'typeNewsletter':
                return new Checkbox($this->webdriver, $this->webdriver->byId($base_id . 'newsletter'));
                break;
            case 'typePoll':
                return new Checkbox($this->webdriver, $this->webdriver->byId($base_id . 'poll'));
                break;
            case 'typePaddleCirroPage':
                return new Checkbox($this->webdriver, $this->webdriver->byId($base_id . 'paddle-cirro-page'));
                break;
            case 'typePaddleProduct':
                return new Checkbox($this->webdriver, $this->webdriver->byId($base_id . 'paddle-product'));
                break;
            case 'typeOffer':
                return new Checkbox($this->webdriver, $this->webdriver->byId($base_id . 'offer'));
                break;
            case 'typePaddlePublication':
                return new Checkbox($this->webdriver, $this->webdriver->byId($base_id . 'paddle-publication'));
                break;
            case 'typePaddleEblPage':
                return new Checkbox($this->webdriver, $this->webdriver->byId($base_id . 'paddle-ebl-page'));
                break;
        }
        throw new FormFieldNotDefinedException($name);
    }
}
