<?php

/**
 * @file
 * Contains \Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleRate\ConfigurePage\ConfigureForm.
 */

namespace Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleRate\ConfigurePage;

use Kanooh\Paddle\Pages\Element\Form\Checkbox;
use Kanooh\Paddle\Pages\Element\Form\Form;
use Kanooh\Paddle\Pages\Element\Form\FormFieldNotDefinedException;

/**
 * Class representing the Paddle Rate configuration form.
 *
 * @property Checkbox $typePaddleAdvancedSearchPage
 * @property Checkbox $typeBasicPage
 * @property Checkbox typeLandingPage
 * @property Checkbox $typePaddleOverviewPage
 * @property Checkbox $typeOrganizationUnit
 * @property Checkbox $typePaddleMapsPage
 * @property Checkbox $typeNewsItem
 * @property Checkbox $typeContactPerson
 * @property Checkbox $typeSimpleContactPage
 * @property Checkbox $typePaddleFormbuilderPage
 * @property Checkbox $typeCalendarItem
 * @property Checkbox $typeQuiz
 * @property Checkbox $typeNewsletter
 * @property Checkbox $typePoll
 * @property Checkbox $typeOffer
 * @property Checkbox $typePaddleCirroPage
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
        $base_id = 'edit-paddle-rate-content-types-';
        switch ($name) {
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
            case 'typeOffer':
                return new Checkbox($this->webdriver, $this->webdriver->byId($base_id . 'offer'));
                break;
            case 'typePaddleCirroPage':
                return new Checkbox($this->webdriver, $this->webdriver->byId($base_id . 'paddle-cirro-page'));
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
