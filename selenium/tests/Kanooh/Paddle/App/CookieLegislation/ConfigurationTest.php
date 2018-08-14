<?php

/**
 * @file
 * Contains \Kanooh\Paddle\App\CookieLegislation\ConfigurationTest.
 */

namespace Kanooh\Paddle\App\CookieLegislation;

use Kanooh\Paddle\Apps\CookieLegislation;
use Kanooh\Paddle\Apps\Multilingual;
use Kanooh\Paddle\Pages\Admin\Apps\PaddleApps\PaddleCookieLegislation\ConfigurePage\ConfigurePage;
use Kanooh\Paddle\Pages\FrontPage\FrontPage;
use Kanooh\Paddle\Utilities\AppService;
use Kanooh\Paddle\Utilities\UserSessionService;
use Kanooh\TestDataProvider\AlphanumericTestDataProvider;
use Kanooh\WebDriver\WebDriverTestCase;

/**
 * Performs configuration tests on the Cookie Legislation paddlet.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ConfigurationTest extends WebDriverTestCase
{
    /**
     * @var AlphanumericTestDataProvider
     */
    protected $alphanumericDataProvider;

    /**
     * @var AppService
     */
    protected $appService;

    /**
     * @var ConfigurePage
     */
    protected $configurePage;

    /**
     * @var FrontPage
     */
    protected $frontPage;

    /**
     * @var UserSessionService
     */
    protected $userSessionService;

    /**
     * {@inheritdoc}
     */
    public function setUpPage()
    {
        parent::setUpPage();

        // Prepare some variables for later use.
        $this->alphanumericDataProvider = new AlphanumericTestDataProvider();
        $this->configurePage = new ConfigurePage($this);
        $this->frontPage = new FrontPage($this);
        $this->userSessionService = new UserSessionService($this);

        // Enable the app if it is not yet enabled.
        $this->appService = new AppService($this, $this->userSessionService);
        $this->appService->enableApp(new CookieLegislation());

        $this->userSessionService->login('SiteManager');
    }

    /**
     * Tests the configuring of the Cookie Legislation Paddlet.
     */
    public function testCookieLegislationConfiguration()
    {
        // Make sure i18n is disabled.
        $this->appService->disableApp(new Multilingual);
        $form_content = $this->fillCookieForm();

        // Lets make sure that all is saved, navigate away and come back to the configuration page.
        $this->frontPage->go();
        $this->configurePage->go();
        $this->assertEquals($form_content['agree_text'], $this->configurePage->form->agree->getContent());
        $this->assertEquals($form_content['disagree_text'], $this->configurePage->form->disagree->getContent());
        $this->assertEquals($form_content['privacy_policy_link'], $this->configurePage->form->privacyPolicyLink->getContent());
        $this->assertTrue($this->configurePage->form->enable->isChecked());
        $this->assertTrue($this->configurePage->form->privacyPolicyTarget->isChecked());

        // Now Lets logout and see if the anonymous user will get the popup msg.
        $this->userSessionService->logout();
        $this->url('/?whatever');
        $this->byCssSelector('.sliding-popup-top');
        $this->byCssSelector('.sliding-popup-top #popup-buttons .agree-button');
        $this->byCssSelector('.sliding-popup-top #popup-buttons .find-more-button');
    }

    /**
     * Tests the configuring of the Cookie Legislation Paddlet.
     */
    public function testCookieLegislationConfigurationI18n()
    {
        // Lets test with i18n enabled.
        $this->appService->enableApp(new Multilingual);

        $form_content = $this->fillCookieForm();

        // Switch to another language and fill some fields.
        $this->configurePage->languageSwitcher->switchLanguage('fr');
        $this->configurePage->form->agree->fill($form_content['agree_text'] . 'fr');
        $this->configurePage->form->disagree->fill($form_content['disagree_text'] . 'fr');
        $this->configurePage->contextualToolbar->buttonSave->click();

        // Now go back to NL and make sure the field values are the NL values not the FR ones.
        $this->configurePage->languageSwitcher->switchLanguage('nl');
        $this->assertNotEquals($form_content['disagree_text'] . 'fr', $this->configurePage->form->disagree->getContent());
        $this->assertEquals($form_content['disagree_text'], $this->configurePage->form->disagree->getContent());
        $this->assertEquals($form_content['agree_text'], $this->configurePage->form->agree->getContent());
        $this->assertNotEquals($form_content['agree_text'] . 'fr', $this->configurePage->form->agree->getContent());

        // Now make sure that the content will fallback to NL if not filled for a specific language.
        $this->configurePage->languageSwitcher->switchLanguage('de');
        $this->assertEquals($form_content['disagree_text'], $this->configurePage->form->disagree->getContent());
    }

    /**
     * Helper to fill the eu cookie legislation form
     *
     * @return array
     *  The fields content of the form.
     */
    protected function fillCookieForm()
    {
        $agree_text = $this->alphanumericDataProvider->getValidValue();
        $popup_message = $this->alphanumericDataProvider->getValidValue();
        $disagree_text = $this->alphanumericDataProvider->getValidValue();
        $privacy_policy_link = 'http://www.' . $this->alphanumericDataProvider->getValidValue() . '.com';

        // Go the the paddlet configuration page.
        $this->configurePage->go();
        $this->configurePage->form->enable->check();
        $this->configurePage->form->agree->fill($agree_text);
        $this->configurePage->form->disagree->fill($disagree_text);
        $this->configurePage->form->privacyPolicyLink->fill($privacy_policy_link);
        $this->configurePage->form->privacyPolicyTarget->check();
        $this->configurePage->form->popupMessage->setBodyText($popup_message);
        $this->configurePage->contextualToolbar->buttonSave->click();

        $form_content = array(
            'agree_text' => $agree_text,
            'popup_message' => $popup_message,
            'disagree_text' => $disagree_text,
            'privacy_policy_link' => $privacy_policy_link,
        );

        return $form_content;
    }
}
